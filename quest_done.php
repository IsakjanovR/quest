<?php
namespace Apo100l\Quest;
require "vendor/autoload.php";

class Statistics extends QuestAbstract
{

    private $sql_all_documents = 'SELECT `id`, COUNT(id), 
                               `finish_time`, 
                              SUM(amount) 
                              FROM `payments` 
                              WHERE `finish_time` > \'2015-07-20 00:00:00\' 
                              AND `finish_time` < \'2015-11-01 00:00:00\'';

    private $sql_whith_documents = 'SELECT payments.id, COUNT(payments.id), 
                              documents.entity_id, payments.finish_time, 
                              SUM(payments.amount) 
                              FROM `payments`, `documents` 
                              WHERE payments.finish_time > \'2015-07-20 00:00:00\' 
                              AND payments.finish_time < \'2015-11-01 00:00:00\' 
                              AND payments.id = documents.entity_id';


    private function showResult()
    {
        $db_connect = $this->getDb();

        $result_all_documents = $db_connect->query($this->sql_all_documents);
        $result_whith_documents = $db_connect->query($this->sql_whith_documents);


        while ($resArr = $result_whith_documents->fetch($db_connect::FETCH_ASSOC)) {
            $finish_Result_whith[1] = $resArr['SUM(payments.amount)'];
            $finish_Result_whith[2] = $resArr['COUNT(payments.id)'];
        };

        while ($resArr = $result_all_documents->fetch($db_connect::FETCH_ASSOC)) {
            $finish_Result_all[1] = $resArr['SUM(amount)'];
            $finish_Result_all[2] = $resArr['COUNT(id)'];
        }
        $finish_Result_whithout[1] = $finish_Result_all[1] - $finish_Result_whith[1];
        $finish_Result_whithout[2] = $finish_Result_all[2] - $finish_Result_whith[2];

        global $argv;


//        echo $date_from;
//        echo $date_to;
        switch (count($argv)) {
            case 1:
                echo "Use parameter 'statistics' with '--without-documents' or '--with-documents' options to use the script";
                break;
            case 2:
                switch ($argv[1]) {
                    case "--all-documents":

                        echo "amount " . $finish_Result_all[1] . " ";
                        echo "count " . $finish_Result_all[2] . " ";
                        echo "--all-documents argument used";
                        break;
                    case "--with-documents":

                        echo "amount " . $finish_Result_whith[1] . " ";
                        echo "count " . $finish_Result_whith[2] . " ";
                        echo "--with-documents argument used";
                        break;
                    case "--without-documents":

                        echo "amount " . $finish_Result_whithout[1] . " ";
                        echo "count " . $finish_Result_whithout[2] . " ";
                        echo "--without-documents argument used";
                        break;
                    default:
                        echo "Add '--without-documents' or '--with-documents' parameters ";
                }
                break;
            default:
                echo "Use parameter 'statistics' with '--without-documents' or '--with-documents' options to use the script";
        }

    }

    public function run()
    {
        return $this->showResult();
    }
}

$showStats = new Statistics();
$showStats->run();





