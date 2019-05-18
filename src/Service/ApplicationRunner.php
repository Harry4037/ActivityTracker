<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApplicationRunner {

    private $app; /* @var $app Application */
    private $group; /* @var $group Group */
    private $entity; /* @var $entity Entity */
    private $xml;
    private $browser;

    public function __construct($group, $application, $entity) {
        $this->app = $application;
        $this->group = $group;
        $this->entity = $entity;
        $this->namespace = "isim_";
        $this->xml = null;
//        $this->browser = new mySfWebBrowser();
    }

    /**
     * Loads the entity for the groupApp
     *
     * @return ResultSet
     */
    public function load() {
        $rs = self::getCurrentEntityData($this->group->getGroupID(), $this->app->getApplicationid(), $this->entity->getEntityid());

        // if query returned results, then this app/group/entity has already been loaded previously
        // otherwise we need to transfer the data for this app/entity from the baseApplicationData table
        // to the transactionData table and then retrieve the results afterwards
        if (count($rs) == 0) {
            try {
                $connection = Propel::getConnection();
                //$connection->begin();

                $c = new Criteria();
                $c->add(CommandPeer::DESCRIPTION, "load");
                $command = CommandPeer::doSelectOne($c);
                /* @var $command Command */

                // insert a 'load' transaction
                $transaction = new UserTransaction();
                $transaction->setGroupID($this->group->getGroupID());
                $transaction->setApplicationid($this->app->getApplicationid());
                $transaction->setUserid(sfContext::getInstance()->getUser()->getUserID());
                $transaction->setEntityid($this->entity->getEntityID());
                $transaction->setCommandid($command->getCommandid());
                $transaction->setError(1);
                $transaction->setComment("Copy of Base Data");
                $transaction->save();

                TransactionDataPeer::insertBaseApplicationValues($transaction->getTransactionID(), $this->group->getGroupID(), $this->app->getApplicationid(), $this->entity->getEntityID(), $this->getXMLdata());

                $transaction->setError(0);
                $transaction->save();

                //$connection->commit();
            } catch (Exception $e) {
                //$connection->rollback();
                $transaction->setError(1);
                $transaction->save();
                //$connection->commit();
                throw $e;
            }
        }

        // if the entity is an aggregate, and it needs to be solved (a country in the agg has been updated), then solve
        if ($this->entity->isAggregateInApplication($this->app->getApplicationID()) && $this->entity->NeedsSolveForGroupApplication($this->app->getApplicationid(), $this->group->getGroupid())) {
            $rs = $this->solve("Load Aggregate", array());
        } else { // the entity is a country, or its an agg and does not need solving			
            $rs = self::getCurrentEntityData($this->group->getGroupID(), $this->app->getApplicationid(), $this->entity->getEntityid());
        }

        return $rs;
    }

    /**
     * Solves for the groupapp entity
     *
     * @param string $comment
     * @param array $data
     * @param boolean $recalculateAddFactors
     * @return ResultSet
     */
    public function solve($comment, $data, $recalculateAddFactors = false) {
        $c = new Criteria();
        $c->add(CommandPeer::DESCRIPTION, "solve");
        $command = CommandPeer::doSelectOne($c);
        /* @var $command Command */

        ini_set('arg_separator.output', '&'); // in order to parse the URL parameters properly, the separator needs to be an & (just in case the php.ini is not set correctly, this is a fallback)
        // create a readable structure out of the original $data array as follows:
        // $newData[mnemonic] = array("mode"=>"x","displayType"=>"g","inputValues"=>array(1,2,3,4)) etc...		

        $this->browser->setRequestValue("group", array(
            "id" => $this->group->getGroupid()
        ));

        $this->browser->setRequestValue("app", array(
            "id" => $this->app->getApplicationid(),
            "startDataPeriod" => $this->app->getStartdataperiod(),
            "endDataPeriod" => $this->app->getEnddataperiod(),
            "startSimulationPeriod" => $this->app->getStartsimulationperiod(),
            "endSimulationPeriod" => $this->app->getEndsimulationperiod(),
            "startDisplayPeriod" => $this->app->getStartdisplayperiod(),
            "endDisplayPeriod" => $this->app->getEnddisplayperiod(),
            "startUserSolvePeriod" => $this->app->getStartusersolveperiod(),
            "endUserSolvePeriod" => $this->app->getEndusersolveperiod()
        ));

        $this->browser->setRequestValue("entity", $this->entity->getEntityCode());
        $this->browser->setRequestValue("isAggregate", $this->entity->isAggregateInApplication($this->app->getApplicationid()));
        $this->browser->setRequestValue("command", $command->getDescription());
        $this->browser->setRequestValue("recalculateAddFactors", $recalculateAddFactors);

        $dataToSubmit = array();
        foreach ($data as $dataPoint) {
            $dataToSubmit[strtoupper($dataPoint["mnemonic"])] = array(
                "mode" => $dataPoint["mode"],
                "displayType" => $dataPoint["displayType"],
                "inputValues" => array()
            );

            if (array_key_exists("userHistEnd", $dataPoint)) {
                $dataToSubmit[strtoupper($dataPoint["mnemonic"])]["userHistEnd"] = $dataPoint["userHistEnd"];
            }

            if (array_key_exists("actualHistEnd", $dataPoint)) {
                $dataToSubmit[strtoupper($dataPoint["mnemonic"])]["actualHistEnd"] = $dataPoint["actualHistEnd"];
            }

            if ($this->app->getFrequency() == "a") {
                for ($i = $this->app->getStartdisplayperiod(); $i <= $this->app->getEnddisplayperiod(); $i++) {
                    $dataToSubmit[$dataPoint["mnemonic"]]["inputValues"][] = $dataPoint['y' . $i];
                }
            } elseif ($this->app->getFrequency() == "q") {
                $thisQuarter = $this->app->getStartDisplayPeriod();
                $endQuarter = $this->app->getEndDisplayPeriod();
                do {
                    $dataToSubmit[$dataPoint["mnemonic"]]["inputValues"][] = $dataPoint['y' . $thisQuarter];
                    $quarter = substr($thisQuarter, -1);
                    $year = substr($thisQuarter, 0, 4);
                    if ($quarter < 4) {
                        $quarter++;
                    } else {
                        $quarter = 1;
                        $year++;
                    }
                    $thisQuarter = $year . "Q" . $quarter;
                } while ($thisQuarter <= $endQuarter);
            }
        }

        $this->browser->setRequestValue("data", $dataToSubmit);

        try {
            $connection = Propel::getConnection();
            //$connection->begin();
            // insert a 'solve' transaction
            $transaction = new UserTransaction();
            $transaction->setGroupID($this->group->getGroupID());
            $transaction->setApplicationid($this->app->getApplicationid());
            $transaction->setUserid(sfContext::getInstance()->getUser()->getUserID());
            $transaction->setEntityid($this->entity->getEntityID());
            $transaction->setCommandid($command->getCommandid());
            $transaction->setError(1);
            $transaction->setComment($comment);
            $transaction->save();

            $this->browser->setRequestValue("uid", $transaction->getTransactionid());

            // add user to queue
            $queueEntry = new TransactionQueue();
            $queueEntry->setTransactionid($transaction->getTransactionid());
            $queueEntry->save();

            // wait until user has priority
            while (!$transaction->firstInQueue()) {
                usleep(500000);
            }

            $this->browser->executeRequest($this->app);

            // remove transaction from queue
            $queueEntry->delete();

            if ($this->browser->hasResponseValue("error") && $this->browser->getResponseValue("error") === true) {
                $order = array("\r\n", "\n", "\r");
                $replace = '<br>';
                throw new Exception(str_replace($order, $replace, $this->browser->getResponseValue("errorText")));
            } else {
                // parse through result and insert into original $data array as outputValues                              		
                $responseData = $this->browser->getResponseData();
                $xml = $this->getXMLdata();

                $found = array();
                $notfound = array();

                $dataVals = array_key_exists("data", $responseData) ? $responseData["data"] : $responseData;

                foreach ($dataVals as $code => $data) {
                    $mnemonic = strtoupper($code);
                    if (is_array($data)) { // only process this data point if the output values are a valid array
                        // remove iso entityCode from var name
                        $mnemonic = substr($mnemonic, 3);
                        $userHistEnd = "NULL";
                        $actualHistEnd = "NULL";

                        if (isset($dataToSubmit[$mnemonic])) {
                            $found[] = $mnemonic;
                            $varMode = $dataToSubmit[$mnemonic]["mode"];
                            $displayType = $dataToSubmit[$mnemonic]["displayType"];
                            if (array_key_exists("userHistEndVals", $responseData)) { // if the backend sent back a userHistEnd val, use it
                                $userHistEnd = $responseData["userHistEndVals"][$code];
                            } elseif (array_key_exists("userHistEnd", $dataToSubmit[$mnemonic])) { // otherwise, if the frontend submitted a userHistEnd val, use it
                                $userHistEnd = $dataToSubmit[$mnemonic]["userHistEnd"];
                            }
                            if (array_key_exists("actualHistEnd", $dataToSubmit[$mnemonic])) {
                                $actualHistEnd = $dataToSubmit[$mnemonic]["actualHistEnd"];
                            }
                        } else {
                            $notfound[] = $mnemonic;
                            try {
                                $varMode = self::getDefaultModeOfVariable($xml, $mnemonic);
                            } catch (Exception $e) {
                                // if all else fails, just make the var endogenous
                                $varMode = "e";
                            }
                            try {
                                $displayType = self::getDefaultDisplayTypeOfVariable($xml, $mnemonic);
                            } catch (Exception $e) {
                                $displayType = "g";
                            }
                        }
                        $inputValues = (!isset($dataToSubmit[$mnemonic])) ? null : implode(",", $dataToSubmit[$mnemonic]["inputValues"]);
                        $outputValues = implode(",", $data);
                        //$dataToSubmit[$mnemonic]["outputValues"] = $outputValues;

                        /* INSERT INTO transactionData
                          VALUES(1,(SELECT variableID FROM applicationmnemonics WHERE mnemonic="" AND applicationID=?),
                          (SELECT modeID FROM variablemodelkp WHERE abbreviation=""),
                          (SELECT displayTypeID FROM variableDisplayTypeLKP WHERE abbreviation=?),"","","","") */
                        $query = "INSERT INTO %s VALUES(?,(SELECT %s FROM %s WHERE %s=? AND %s=?),(SELECT %s FROM %s WHERE %s=?),(SELECT %s FROM %s WHERE %s=?),?,?,?,?)";
                        $query = sprintf($query, TransactionDataPeer::TABLE_NAME, ApplicationMnemonicPeer::VARIABLEID, ApplicationMnemonicPeer::TABLE_NAME, ApplicationMnemonicPeer::MNEMONIC, ApplicationMnemonicPeer::APPLICATIONID, VariableModePeer::MODEID, VariableModePeer::TABLE_NAME, VariableModePeer::ABBREVIATION, VariableDisplayTypePeer::DISPLAYTYPEID, VariableDisplayTypePeer::TABLE_NAME, VariableDisplayTypePeer::ABBREVIATION);

                        $stmt = $connection->prepareStatement($query);
                        $stmt->setInt(1, $transaction->getTransactionid());
                        $stmt->setString(2, $mnemonic);
                        $stmt->setString(3, $this->app->getApplicationid());
                        $stmt->setString(4, $varMode);
                        $stmt->setString(5, $displayType);
                        if ($userHistEnd == "NULL") {
                            $stmt->setNull(6);
                        } else {
                            $stmt->setString(6, $userHistEnd);
                        }
                        if ($actualHistEnd == "NULL") {
                            $stmt->setNull(7);
                        } else {
                            $stmt->setString(7, $actualHistEnd);
                        }
                        $stmt->setString(8, $inputValues);
                        $stmt->setString(9, $outputValues);

                        $stmt->executeQuery($query);
                    } elseif ($mnemonic == "MSG") { // system message from backend (like negative values, for example)          				
                        $transaction->setSystemMessage($data);
                        $transaction->save();
                    }
                }
            }

            // set transaction as completed     		     		     		     		
            $transaction->setError(0);
            $transaction->save();
            //$connection->commit();
        } catch (Exception $e) {
            //$connection->rollback();
            $transaction->setError(1);
            $transaction->save();
            if (is_object($queueEntry) && !$queueEntry->isDeleted()) {
                $queueEntry->delete();
            }
            // $connection->commit();		     
            throw $e;
        }

        $rs = self::getCurrentEntityData($this->group->getGroupID(), $this->app->getApplicationid(), $this->entity->getEntityid());

        return $rs;
    }

    public function recalculateAddFactors() {
        $c = new Criteria();
        $c->add(CommandPeer::DESCRIPTION, "addfactor");
        $command = CommandPeer::doSelectOne($c);
        /* @var $command Command */

        ini_set('arg_separator.output', '&'); // in order to parse the URL parameters properly, the separator needs to be an & (just in case the php.ini is not set correctly, this is a fallback)
        // create a readable structure out of the original $data array as follows:
        // $newData[mnemonic] = array("mode"=>"x","displayType"=>"g","inputValues"=>array(1,2,3,4)) etc...		

        $this->browser->setRequestValue("group", array(
            "id" => $this->group->getGroupid()
        ));

        $this->browser->setRequestValue("app", array(
            "id" => $this->app->getApplicationid(),
            "startDataPeriod" => $this->app->getStartdataperiod(),
            "endDataPeriod" => $this->app->getEnddataperiod(),
            "startSimulationPeriod" => $this->app->getStartsimulationperiod(),
            "endSimulationPeriod" => $this->app->getEndsimulationperiod(),
            "startDisplayPeriod" => $this->app->getStartdisplayperiod(),
            "endDisplayPeriod" => $this->app->getEnddisplayperiod(),
            "startUserSolvePeriod" => $this->app->getStartusersolveperiod(),
            "endUserSolvePeriod" => $this->app->getEndusersolveperiod()
        ));

        $this->browser->setRequestValue("entity", $this->entity->getEntityCode());
        $this->browser->setRequestValue("isAggregate", $this->entity->isAggregateInApplication($this->app->getApplicationid()));
        $this->browser->setRequestValue("command", $command->getDescription());

        try {
            $connection = Propel::getConnection();
            //$connection->begin();
            // insert a 'solve' transaction
            $transaction = new UserTransaction();
            $transaction->setGroupID($this->group->getGroupID());
            $transaction->setApplicationid($this->app->getApplicationid());
            $transaction->setUserid(sfContext::getInstance()->getUser()->getUserID());
            $transaction->setEntityid($this->entity->getEntityID());
            $transaction->setCommandid($command->getCommandid());
            $transaction->setError(1);
            $transaction->setComment($comment);
            $transaction->save();

            $this->browser->setRequestValue("uid", $transaction->getTransactionid());

            // add user to queue
            $queueEntry = new TransactionQueue();
            $queueEntry->setTransactionid($transaction->getTransactionid());
            $queueEntry->save();

            // wait until user has priority
            while (!$transaction->firstInQueue()) {
                usleep(500000);
            }

            $this->browser->executeRequest($this->app);

            // remove transaction from queue
            $queueEntry->delete();

            if ($this->browser->hasResponseValue("error") && $this->browser->getResponseValue("error") === true) {
                $order = array("\r\n", "\n", "\r");
                $replace = '<br>';
                throw new Exception(str_replace($order, $replace, $this->browser->getResponseValue("errorText")));
            } else {
                // parse through result and insert into original $data array as outputValues                              		
                $responseData = $this->browser->getResponseData();
                $xml = $this->getXMLdata();

                foreach ($responseData as $mnemonic => $data) {
                    if (is_array($data)) { // only process this data point if the output values are a valid array
                        // remove iso entityCode from var name
                        $mnemonic = substr($mnemonic, 3);
                        if (isset($dataToSubmit[$mnemonic])) {
                            $varMode = $dataToSubmit[$mnemonic]["mode"];
                            $displayType = $dataToSubmit[$mnemonic]["displayType"];
                        } else {
                            try {
                                $varMode = self::getDefaultModeOfVariable($xml, $mnemonic);
                            } catch (Exception $e) {
                                // if all else fails, just make the var endogenous
                                $varMode = "e";
                            }
                            try {
                                $displayType = self::getDefaultDisplayTypeOfVariable($xml, $mnemonic);
                            } catch (Exception $e) {
                                $displayType = "g";
                            }
                        }
                        $inputValues = (!isset($dataToSubmit[$mnemonic])) ? null : implode(",", $dataToSubmit[$mnemonic]["inputValues"]);
                        $outputValues = implode(",", $data);
                        //$dataToSubmit[$mnemonic]["outputValues"] = $outputValues;

                        /* INSERT INTO transactionData
                          VALUES(1,(SELECT variableID FROM applicationmnemonics WHERE mnemonic="" AND applicationID=?),
                          (SELECT modeID FROM variablemodelkp WHERE abbreviation=""),
                          (SELECT displayTypeID FROM variableDisplayTypeLKP WHERE abbreviation=?),"","") */
                        $query = "INSERT INTO %s VALUES(?,(SELECT %s FROM %s WHERE %s=? AND %s=?),(SELECT %s FROM %s WHERE %s=?),(SELECT %s FROM %s WHERE %s=?),?,?)";
                        $query = sprintf($query, TransactionDataPeer::TABLE_NAME, ApplicationMnemonicPeer::VARIABLEID, ApplicationMnemonicPeer::TABLE_NAME, ApplicationMnemonicPeer::MNEMONIC, ApplicationMnemonicPeer::APPLICATIONID, VariableModePeer::MODEID, VariableModePeer::TABLE_NAME, VariableModePeer::ABBREVIATION, VariableDisplayTypePeer::DISPLAYTYPEID, VariableDisplayTypePeer::TABLE_NAME, VariableDisplayTypePeer::ABBREVIATION);

                        $stmt = $connection->prepareStatement($query);
                        $stmt->setInt(1, $transaction->getTransactionid());
                        $stmt->setString(2, $mnemonic);
                        $stmt->setString(3, $this->app->getApplicationid());
                        $stmt->setString(4, $varMode);
                        $stmt->setString(5, $displayType);
                        $stmt->setString(6, $inputValues);
                        $stmt->setString(7, $outputValues);

                        $stmt->executeQuery($query);
                    }
                }
            }

            // set transaction as completed     		     		     		     		
            $transaction->setError(0);
            $transaction->save();
            //$connection->commit();
        } catch (Exception $e) {
            //$connection->rollback();
            $transaction->setError(1);
            $transaction->save();
            if (is_object($queueEntry) && !$queueEntry->isDeleted()) {
                $queueEntry->delete();
            }
            // $connection->commit();		     
            throw $e;
        }

        $rs = self::getCurrentEntityData($this->group->getGroupID(), $this->app->getApplicationid(), $this->entity->getEntityid());

        return $rs;
    }

    /**
     * Runs an update for the groupApp entity
     *
     * @param string $comment
     * @return int, transactionID
     */
    public function update($comment) {
        $c = new Criteria();
        $c->add(CommandPeer::DESCRIPTION, "update");
        $command = CommandPeer::doSelectOne($c);
        /* @var $command Command */

        ini_set('arg_separator.output', '&'); // in order to parse the URL parameters properly, the separator needs to be an & (just in case the php.ini is not set correctly, this is a fallback)
        // create a readable structure out of the original $data array as follows:
        // $newData[mnemonic] = array("mode"=>"x","displayType"=>"g","inputValues"=>array(1,2,3,4)) etc...		
        $this->browser->setRequestValue("group", array(
            "id" => $this->group->getGroupid()
        ));

        $this->browser->setRequestValue("app", array(
            "id" => $this->app->getApplicationid(),
            "startDataPeriod" => $this->app->getStartdataperiod(),
            "endDataPeriod" => $this->app->getEnddataperiod(),
            "startSimulationPeriod" => $this->app->getStartsimulationperiod(),
            "endSimulationPeriod" => $this->app->getEndsimulationperiod(),
            "startDisplayPeriod" => $this->app->getStartdisplayperiod(),
            "endDisplayPeriod" => $this->app->getEnddisplayperiod(),
            "startUserSolvePeriod" => $this->app->getStartusersolveperiod(),
            "endUserSolvePeriod" => $this->app->getEndusersolveperiod()
        ));

        $this->browser->setRequestValue("entity", $this->entity->getEntityCode());
        $this->browser->setRequestValue("isAggregate", $this->entity->isAggregateInApplication($this->app->getApplicationid()));
        $this->browser->setRequestValue("command", $command->getDescription());

        try {
            $connection = Propel::getConnection();

            // insert a 'update' transaction
            $transaction = new UserTransaction();
            $transaction->setGroupID($this->group->getGroupID());
            $transaction->setApplicationid($this->app->getApplicationid());
            $transaction->setUserid(sfContext::getInstance()->getUser()->getUserID());
            $transaction->setEntityid($this->entity->getEntityID());
            $transaction->setCommandid($command->getCommandid());
            $transaction->setError(1);
            $transaction->setComment($comment);
            $transaction->save();

            $this->browser->setRequestValue("uid", $transaction->getTransactionid());

            // add user to queue
            $queueEntry = new TransactionQueue();
            $queueEntry->setTransactionid($transaction->getTransactionid());
            $queueEntry->save();

            // wait until user has priority
            while (!$transaction->firstInQueue()) {
                usleep(500000);
            }

            $this->browser->executeRequest($this->app);

            // remove transaction from queue
            $queueEntry->delete();

            if ($this->browser->hasResponseValue("error") && $this->browser->getResponseValue("error") === true) {
                $order = array("\r\n", "\n", "\r");
                $replace = '<br>';
                throw new Exception(str_replace($order, $replace, $this->browser->getResponseValue("errorText")));
            } else {
                /* INSERT INTO transactionData (transactionID,variableID,modeID,displayTypeID,userHistEnd,actualHistEnd,outputValues)
                  SELECT ?,variableID,modeID,displayTypeID,userHistEnd,actualHistEnd,outputValues
                  FROM transactionData
                  WHERE transactionData.transactionID=
                  (SELECT transactionID
                  FROM transactions,commandLKP
                  WHERE transactions.groupID=?
                  AND transactions.applicationID=?
                  AND transactions.entityID=?
                  AND transactions.error=0
                  AND commandLKP.description="solve"
                  AND transactions.commandID=commandLKP.commandID
                  ORDER BY transactions.created_at DESC
                  LIMIT 1) */
                $query = "INSERT INTO %s (%s,%s,%s,%s,%s,%s,%s) SELECT ?,%s,%s,%s,%s,%s,%s FROM %s WHERE %s=(SELECT %s FROM %s,%s WHERE %s=? AND %s=? AND %s=? AND %s=? AND %s=? AND %s=%s ORDER BY %s DESC LIMIT 1)";
                $query = sprintf($query, TransactionDataPeer::TABLE_NAME, TransactionDataPeer::TRANSACTIONID, TransactionDataPeer::VARIABLEID, TransactionDataPeer::MODEID, TransactionDataPeer::DISPLAYTYPEID, TransactionDataPeer::USERHISTEND, TransactionDataPeer::ACTUALHISTEND, TransactionDataPeer::OUTPUTVALUES, TransactionDataPeer::VARIABLEID, TransactionDataPeer::MODEID, TransactionDataPeer::DISPLAYTYPEID, TransactionDataPeer::USERHISTEND, TransactionDataPeer::ACTUALHISTEND, TransactionDataPeer::OUTPUTVALUES, TransactionDataPeer::TABLE_NAME, TransactionDataPeer::TRANSACTIONID, UserTransactionPeer::TRANSACTIONID, UserTransactionPeer::TABLE_NAME, CommandPeer::TABLE_NAME, UserTransactionPeer::GROUPID, UserTransactionPeer::APPLICATIONID, UserTransactionPeer::ENTITYID, UserTransactionPeer::ERROR, CommandPeer::DESCRIPTION, UserTransactionPeer::COMMANDID, CommandPeer::COMMANDID, UserTransactionPeer::CREATED_AT);
                $stmt = $connection->prepareStatement($query);
                $stmt->setInt(1, $transaction->getTransactionid());
                $stmt->setInt(2, $this->group->getGroupid());
                $stmt->setInt(3, $this->app->getApplicationid());
                $stmt->setInt(4, $this->entity->getEntityid());
                $stmt->setInt(5, 0);
                $stmt->setString(6, "solve");
                $rs = $stmt->executeQuery();
            }

            // set transaction as completed     		     		
            $transaction->setError(0);
            $transaction->save();
        } catch (Exception $e) {
            //$connection->rollback();
            $transaction->setError(1);
            $transaction->save();
            if (is_object($queueEntry) && !$queueEntry->isDeleted()) {
                $queueEntry->delete();
            }
            throw $e;
        }
        return $transaction->getTransactionid();
    }

    /**
     * Gets the xml data for use	 
     * 
     * @param bool $useCache (if false, will make a request every time to the application server for most up-to-date XML data)
     *
     * @return SimpleXMLElement (the xml data)
     */
    public function getXMLdata($useCache = true) {
        if ($useCache === true && !is_null($this->xml) && $this->xml !== false)
            return $this->xml;

        $this->browser->setRequestValue("command", "getXMLfile");
        $this->browser->setRequestValue("app", array(
            "id" => $this->app->getApplicationID()
        ));
        $this->browser->setRequestValue("group", array(
            "id" => $this->group->getGroupID()
        ));
        if (!is_null($this->entity)) {
            $this->browser->setRequestValue("entity", $this->entity->getEntityCode());
            $this->browser->setRequestValue("isAggregate", $this->entity->isAggregateInApplication($this->app->getApplicationid()));
        }

        $this->browser->executeRequest($this->app);
        $this->xml = simplexml_load_string($this->browser->getResponseValue("xml"));

        if ($this->xml === false)
            throw new Exception("Invalid XML data returned from application server.");

        return $this->xml;
    }

    public static function getEntityDataForTransactionID($transactionID) {
        $connection = Propel::getConnection();
        /* SELECT transactionData.transactionID,mnemonic,variableLKP.description,variablemodeLKP.abbreviation as mode,variabledisplaytypelkp.abbreviation as displayType,outputValues
          FROM transactionData,applicationmnemonics,variableLKP,variableModeLKP,variableDisplayTypeLKP,usertransactions
          WHERE transactionData.transactionID=13
          AND usertransactions.transactionID=transactionData.transactionID
          AND applicationMnemonics.applicationID=usertransactions.applicationID
          AND transactionData.variableID=applicationmnemonics.variableID
          AND transactionData.variableID=variableLKP.variableID
          AND transactionData.displayTypeID=variableDisplayTypeLKP.displayTypeID
          AND transactionData.modeID=variableModeLKP.modeID */

        $query = 'SELECT %s as transactionID, %s as mnemonic,%s as mode,%s as displayType,%s as outputValues
				FROM %s,%s,%s,%s,%s,%s
				WHERE %s=? AND %s=%s AND %s=%s AND %s=%s AND %s=%s AND %s=%s AND %s=%s';
        $query = sprintf($query, TransactionDataPeer::TRANSACTIONID, ApplicationMnemonicPeer::MNEMONIC, VariableModePeer::ABBREVIATION, VariableDisplayTypePeer::ABBREVIATION, TransactionDataPeer::OUTPUTVALUES, TransactionDataPeer::TABLE_NAME, ApplicationMnemonicPeer::TABLE_NAME, VariablePeer::TABLE_NAME, VariableModePeer::TABLE_NAME, VariableDisplayTypePeer::TABLE_NAME, UserTransactionPeer::TABLE_NAME, TransactionDataPeer::TRANSACTIONID, UserTransactionPeer::TRANSACTIONID, TransactionDataPeer::TRANSACTIONID, ApplicationMnemonicPeer::APPLICATIONID, UserTransactionPeer::APPLICATIONID, TransactionDataPeer::VARIABLEID, ApplicationMnemonicPeer::VARIABLEID, TransactionDataPeer::VARIABLEID, VariablePeer::VARIABLEID, TransactionDataPeer::DISPLAYTYPEID, VariableDisplayTypePeer::DISPLAYTYPEID, TransactionDataPeer::MODEID, VariableModePeer::MODEID);

        $stmt = $connection->prepareStatement($query);
        $stmt->setInt(1, $transactionID);

        return $stmt->executeQuery();
    }

    public static function getCurrentEntityData($groupID, $applicationID, $entityID) {
        $conn = $em->getConnection();

        $sql = 'SELECT currenttransactions.transactionID as transactionID, applicationmnemonics.mnemonic as mnemonic,variablemode.abbreviation as mode,variabledisplaytype.abbreviation as displaytype,transactiondata.userhistend as userhistend,transactiondata.actualhistend as actualhistend,transactiondata.outputvalues as outputvalues
                FROM transactiondata,currenttransactions,applicationmnemonics,variable,variablemode,variabledisplaytype

                WHERE currenttransactions.groupID=' . $groupID . ' AND currenttransactions.applicationID=' . $applicationID . ' AND currenttransactions.entityID=' . $entityID . ' 
                AND transactiondata.transactionID=currenttransactions.transactionID AND transactiondata.variableID=applicationmnemonics.variableID 
                AND transactiondata.variableID=variable.variableID AND transactiondata.displayTypeID=variabledisplaytype.displayTypeID 
                AND transactiondata.modeID=variablemode.modeID AND applicationmnemonics.applicationID=currenttransactions.applicationID';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();

        /* SELECT currenttransactions.transactionID,mnemonic,variableLKP.description,variablemodeLKP.abbreviation as mode,variabledisplaytypelkp.abbreviation as displayType,transactionData.userHistEnd,transactionData.actualHistEnd,outputValues
          FROM transactionData,currenttransactions,applicationmnemonics,variableLKP,variableModeLKP,variableDisplayTypeLKP
          WHERE currenttransactions.groupID=5
          AND currenttransactions.applicationID=2
          AND currenttransactions.entityID=1
          AND transactionData.transactionID=currenttransactions.transactionID
          AND transactionData.variableID=applicationmnemonics.variableID
          AND transactionData.variableID=variableLKP.variableID
          AND transactionData.displayTypeID=variableDisplayTypeLKP.displayTypeID
          AND transactionData.modeID=variableModeLKP.modeID
          AND applicationMnemonics.applicationID=currentTransactions.applicationID */
//        $query = 'SELECT %s as transactionID, %s as mnemonic,%s as mode,%s as displayType,%s as userHistEnd,%s as actualHistEnd,%s as outputValues
//				FROM %s,%s,%s,%s,%s,%s
//				WHERE %s=? AND %s=? AND %s=? AND %s=%s AND %s=%s AND %s=%s AND %s=%s AND %s=%s AND %s=%s';
//
//        $query = sprintf($query, CurrentTransactionPeer::TRANSACTIONID, ApplicationMnemonicPeer::MNEMONIC, VariableModePeer::ABBREVIATION, VariableDisplayTypePeer::ABBREVIATION, TransactionDataPeer::USERHISTEND, TransactionDataPeer::ACTUALHISTEND, TransactionDataPeer::OUTPUTVALUES, TransactionDataPeer::TABLE_NAME, CurrentTransactionPeer::TABLE_NAME, ApplicationMnemonicPeer::TABLE_NAME, VariablePeer::TABLE_NAME, VariableModePeer::TABLE_NAME, VariableDisplayTypePeer::TABLE_NAME, CurrentTransactionPeer::GROUPID, CurrentTransactionPeer::APPLICATIONID, CurrentTransactionPeer::ENTITYID, TransactionDataPeer::TRANSACTIONID, CurrentTransactionPeer::TRANSACTIONID, TransactionDataPeer::VARIABLEID, ApplicationMnemonicPeer::VARIABLEID, TransactionDataPeer::VARIABLEID, VariablePeer::VARIABLEID, TransactionDataPeer::DISPLAYTYPEID, VariableDisplayTypePeer::DISPLAYTYPEID, TransactionDataPeer::MODEID, VariableModePeer::MODEID, ApplicationMnemonicPeer::APPLICATIONID, CurrentTransactionPeer::APPLICATIONID);
//
//        $connection = Propel::getConnection();
//        $stmt = $connection->prepareStatement($query);
//        $stmt->setInt(1, $groupID);
//        $stmt->setInt(2, $applicationID);
//        $stmt->setInt(3, $entityID);
//
//        return $stmt->executeQuery();
    }

    /**
     * Returns array of data between two time periods ($start and $end)
     *
     * @param Application $application
     * @param array $data
     * @param string $start
     * @param string $end
     * 
     * @return array
     */
//    public static function getForecastDataForPeriod(Application $application, array $data, $start, $end) {
//        if ($start < $application->getStartdisplayperiod() || $end > $application->getEnddisplayperiod() || $end < $start) {
//            throw new Exception("Data period out of range.");
//        }
//
//        $offset1 = $start - $application->getStartdisplayperiod();
//
//        return array_slice($data, $offset1, $end - $start + 1);
//    }
//    public static function getDefaultModeOfVariable($xml, $mnemonic) {
//        $node = self::getXMLNodeOfVariable($xml, $mnemonic);
//        $mode = $node->xpath('mode/option[@defaultSelection="true"]');
//        if (count($mode) == 0)
//            throw new Exception("Unable to find default mode for mnemonic");
//        return (string) $mode[0];
//    }
//
//    public static function getDefaultDisplayTypeOfVariable($xml, $mnemonic) {
//        $node = self::getXMLNodeOfVariable($xml, $mnemonic);
//        $displayType = $node->xpath('displayType/option[@defaultSelection="true"]');
//        if (count($displayType) == 0)
//            throw new Exception("Unable to find default display type for mnemonic");
//        return (string) $displayType[0];
//    }

    /* public static function getDefaultEndUserHistOfVariable($xml,$mnemonic)
      {
      $node = self::getXMLNodeOfVariable($xml,$mnemonic);
      $endUserHist = $node->xpath('endUserHist');
      if(count($endUserHist) == 0) throw new Exception("Unable to find default endUserHist for mnemonic");
      return (string) $endUserHist;
      }

      public static function getDefaultEndActualHistOfVariable($xml,$mnemonic)
      {
      $node = self::getXMLNodeOfVariable($xml,$mnemonic);
      $endActualHist = $node->xpath('endActualHist');
      if(count($endActualHist) == 0) throw new Exception("Unable to find default endActualHist for mnemonic");
      return (string) $endActualHist;
      } */

//    private static function getXMLNodeOfVariable($xml, $mnemonic) {
//        $node = $xml->xpath('/grids/grid/rows/row[mnemonic="' . $mnemonic . '"]');
//        if (count($node) == 0)
//            throw new Exception("Unable to find mnemonic in XML string");
//
//        return $node[0];
//    }
}
