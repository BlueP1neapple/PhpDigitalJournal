<?php


namespace JoJoBizzareCoders\DigitalJournal\Infrastructure;


function getSearch(array $request, array $report, array $hashMap): bool{






    foreach ($report as $key => $value)
    {
        if(stripos($key, '_') !== false)
        {
            $id = $value;
            $valueSplit = explode('_', $key);
            $requestSplit = explode('_' , array_keys($request)[0]);
            $getterId = 'get' . ucfirst($valueSplit[1]);
            $getterEntity = 'get' . ucfirst($requestSplit[0]);
            $getterKey = 'get' . ucfirst($requestSplit[1]);
            foreach ($hashMap as $entityKey => $entity){
                if($entityKey === $valueSplit[0])
                {
                    foreach ($entity as $obj)
                    {
                        $a = $obj->{$getterId}();
                        if($a === $value)
                        {
                            $get = $obj->{$getterEntity};
                            $b = $get->{$getterKey}();
                            if($request[array_keys($request)[0]] === $b){
                                return true;
                            }
                        }
                    }
                }
            }
        }
    }


    return false;
}

























