<?php

namespace DigitalEmsInc\Common\Nemsis;


/**
 * Service to determine primary unit index.
 */
class SignatureDateTimeService
{
    public const string SIGNATURE_FACILITY_DATETIME = 'signatures_facility_datetime';
    public const string TI_AT_FACILITY_DATE = 'TI_AtFacilityDate';
    public const string TI_AT_FACILITY_TIME = 'TI_AtFacilityTime';
    public const string TI_LEFT_SCENE_DATE = 'TI_LeftSceneDate';
    public const string TI_LEFT_SCENE_TIME = 'TI_LeftSceneTime';
    public const string TI_AT_PATIENT_DATE = 'TI_AtPatientDate';
    public const string TI_AT_PATIENT_TIME = 'TI_AtPatientTime';
    public const string TI_ARRIVAL_DATE = 'TI_ArrivalDate';
    public const string TI_ARRIVAL_TIME = 'TI_ArrivalTime';
    public const string TI_DISPATCH_DATE = 'TI_DispatchDate';
    public const string TI_DISPATCH_TIME = 'TI_DispatchTime';
    public const string II_INCIDENT_DATE = 'II_IncidentDate';
    public const string II_INCIDENT_TIME = 'II_IncidentTime';
    
    /**
     * Get facility signature datetime.
     *
     * @param array<string, mixed> $incidentData Incident data.
     *
     * @return string|null
     */
    public function getDateTime(array $incidentData, int $unitIndex): ?string
    {
        $signatureDateTime = $incidentData[self::SIGNATURE_FACILITY_DATETIME] ?? null;
        if (!empty($signatureDateTime)) {
            return $signatureDateTime;
        }
        
        $dateTimesByPriorities = [
            [self::TI_AT_FACILITY_DATE, self::TI_AT_FACILITY_TIME],
            [self::TI_LEFT_SCENE_DATE, self::TI_LEFT_SCENE_TIME],
            [self::TI_AT_PATIENT_DATE, self::TI_AT_PATIENT_TIME],
            [self::TI_ARRIVAL_DATE, self::TI_ARRIVAL_TIME],
            [self::TI_DISPATCH_DATE, self::TI_DISPATCH_TIME],
            [self::II_INCIDENT_DATE, self::II_INCIDENT_TIME],
        ];
        foreach ($dateTimesByPriorities as $dateTimeArr) {
            list($dateField, $timeField) = $dateTimeArr;
            $dateField .= $unitIndex;
            $timeField .= $unitIndex;
            $date = array_key_exists($dateField, $incidentData)
                ? $incidentData[$dateField]
                : null;
            $time = array_key_exists($timeField, $incidentData)
                ? $incidentData[$timeField]
                : null;
            if (!$date || !$time) {
                continue;
            }
            return $date . ' ' . $time;
        }
        return null;
    }
}
