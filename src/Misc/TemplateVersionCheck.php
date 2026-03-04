<?php

namespace DigitalEmsInc\Common\Misc;

/**
 * Service to check template versions.
 */
class TemplateVersionCheck
{
    private const PRIMARY_UNIT_SUPPORT_TEMPLATE = '11.1.50';
    
    /**
     * Check if the template supports primary units.
     *
     * @param string $templateName Template name to check
     *
     * @return bool
     */
    public function supportsPrimaryUnit(string $templateName): bool
    {
        return $this->templateIsGreaterOrEqualThen($templateName, self::PRIMARY_UNIT_SUPPORT_TEMPLATE);
    }
    
    /**
     * Is template version greater than compare to version.
     *
     * @param string $templateName Template name
     * @param string $compareTo Compare to
     *
     * @return bool
     */
    public function templateIsGreaterOrEqualThen(string $templateName, string $compareTo): bool
    {
        $version = $this->getVersion($templateName);
        if (!$version) {
            return false;
        }
        return version_compare($version, $compareTo, '>=');
    }
    
    /**
     * Extract version from template name
     *
     * @param string $templateName Template name
     *
     * @return string|null
     */
    private function getVersion(string $templateName): ?string
    {
        if (preg_match('/^(\d+\.\d+\.\d+)[._]\d{8}$/', $templateName, $m)) {
            return $m[1];
        }
        
        return null;
    }
}
