<?php

namespace DigitalEmsInc\Common\Nemsis;

/**
 * Service to determine primary unit index.
 */
class PrimaryUnitService
{
    private const string TI_AT_FACILITY_DATE = 'TI_AtFacilityDate';
    private const string TI_AT_FACILITY_TIME = 'TI_AtFacilityTime';
    private const string TI_UNIT = 'TI_Unit';
    
    /**
     * Get primary Unit index in fieldset.
     *
     * @param array<string, mixed> $incidentData Incident data.
     *
     * @return integer
     */
    public function getPrimaryUnitIndex(array $incidentData): int
    {
        /**
         * List of unit indexes which has filled in AtFacility time.
         */
        $filledAtFacility = [];
        for ($i = 1; $i <= 4; $i++) {
            $atFacilityDate = $incidentData[self::TI_AT_FACILITY_DATE . $i] ?? null;
            $atFacilityTime = $incidentData[self::TI_AT_FACILITY_TIME . $i] ?? null;
            if ($atFacilityDate && $atFacilityTime && !empty($incidentData[self::TI_UNIT . $i])) {
                $filledAtFacility[] = $i;
            }
        }
        
        $unitIndex = null;
        if (count($filledAtFacility) === 1) {
            $unitIndex = current($filledAtFacility);
        }
        
        if (!$unitIndex && count($filledAtFacility) > 1) {
            $unitsReversePriorities = ['B', 'A', 'R'];
            foreach ($unitsReversePriorities as $unitFirstLetter) {
                foreach ($filledAtFacility as $index) {
                    $unit = $incidentData[self::TI_UNIT . $index] ?? null;
                    if ($unit && str_starts_with($unit, $unitFirstLetter)) {
                        $unitIndex = $index;
                    }
                }
            }
        }
        
        if (!$unitIndex && !empty($filledAtFacility)) {
            $unitIndex = current($filledAtFacility);
        }
        
        if (!$unitIndex) {
            for ($i = 1; $i <= 4; $i++) {
                $unit = $incidentData[self::TI_UNIT . $i] ?? null;
                if ($unit) {
                    $unitIndex = $i;
                    break;
                }
            }
        }
        
        return $unitIndex ?: 1;
    }
}
