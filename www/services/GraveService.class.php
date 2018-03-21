<?php
include '../../data/GraveObjectData.class.php';
include '../../models/Grave.class.php';
include 'TrackableObjectService.class.php';

/**
 */

class GraveService extends TrackableObjectService {

    public function __construct(){
    }

    public function getAllGraveEntries() {
        $graveDataClass = new GraveObjectData();
        $allGraveDataObjects =  $graveDataClass -> readGraveObject();
        $allGraveObject = array();

        foreach ($allGraveDataObjects as $graveArray) {
            $graveObject = new Grave($graveArray['idGrave'], $graveArray['firstName'], $graveArray['middleName'], $graveArray['lastName'], $graveArray['birth'], $graveArray['death'], $graveArray['description'], $graveArray['idHistoricFilter'], $graveArray['historicFilterName'],
                $graveArray['idTrackableObject'], $graveArray['longitude'], $graveArray['latitude'], $graveArray['hint'], $graveArray['imageDescription'], $graveArray['imageLocation'], $graveArray['idTypeFilter'], $graveArray['$type']);

            array_push($allGraveObject, $graveObject);
        }
        return $allGraveDataObjects;
    }

    public function createGraveEntry($firstName, $middleName, $lastName, $birth, $death, $description, $idHistoricFilter, $longitude, $latitude, $hint, $imageDescription, $imageLocation, $idTypeFilter) {
        $firstName = filter_var($firstName, FILTER_SANITIZE_STRING);
        $middleName = filter_var($middleName, FILTER_SANITIZE_STRING);
        $lastName = filter_var($lastName, FILTER_SANITIZE_STRING);
        $birth = filter_var (preg_replace("([^0-9/] | [^0-9-])","",htmlentities($birth)));
        $death = filter_var (preg_replace("([^0-9/] | [^0-9-])","",htmlentities($death)));
        $description = filter_var($description, FILTER_SANITIZE_STRING);
        $idHistoricFilter = filter_var($idHistoricFilter, FILTER_SANITIZE_NUMBER_INT);
        if (empty($idHistoricFilter) || $idHistoricFilter == "") {
            $idHistoricFilter = null;
        }

        //create Trackable Object
        $lastInsertIdTrackableObject = $this -> createTrackableObjectEntry($longitude, $latitude, $hint, $imageDescription, $imageLocation, $idTypeFilter);

        //create Grave Object
        $graveDataClass = new GraveObjectData();
        $lastInsertIdGrave =  $graveDataClass -> createGraveObject($firstName, $middleName, $lastName, $birth, $death, $description, $idHistoricFilter);

        //Update Trackable Object to know Grave Object
        $this -> updateObjectEntryID("Grave", $lastInsertIdGrave, $lastInsertIdTrackableObject);
    }

    public function updateGraveEntry($idTrackableObject, $idGrave, $firstName, $middleName, $lastName, $birth, $death, $description, $idHistoricFilter, $longitude, $latitude, $hint, $imageDescription, $imageLocation, $idTypeFilter) {
        $firstName = filter_var($firstName, FILTER_SANITIZE_STRING);
        $middleName = filter_var($middleName, FILTER_SANITIZE_STRING);
        $lastName = filter_var($lastName, FILTER_SANITIZE_STRING);
        $birth = filter_var (preg_replace("([^0-9/] | [^0-9-])","",htmlentities($birth)));
        $death = filter_var (preg_replace("([^0-9/] | [^0-9-])","",htmlentities($death)));
        $description = filter_var($description, FILTER_SANITIZE_STRING);
        $idHistoricFilter = filter_var($idHistoricFilter, FILTER_SANITIZE_NUMBER_INT);
        if (empty($idHistoricFilter) || $idHistoricFilter == "") {
            $idHistoricFilter = null;
        }

        $this ->updateTrackableObjectEntry($idTrackableObject, $longitude, $latitude, $hint, $imageDescription, $imageLocation, $idTypeFilter);

        $graveDataClass = new GraveObjectData();
        $graveDataClass -> updateGraveObject($idGrave, $firstName, $middleName, $lastName, $birth, $death, $description, $idHistoricFilter);
    }

    public function deleteGraveEntry($idGrave) {
        $idGrave = filter_var($idGrave, FILTER_SANITIZE_NUMBER_INT);
        if (empty($idGrave) || $idGrave == "") {
            return;
        } else {
            $graveDataClass = new GraveObjectData();
            $graveDataClass -> deleteGraveObject($idGrave);
        }

    }

}