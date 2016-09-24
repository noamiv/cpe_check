<?php

namespace OSS_SNMP\MIBS;

/**
 * A class for performing SNMP V2 queries on Siemens Ruggedcom WIMAX
 *
 */
class WinBs extends \OSS_SNMP\MIB {
    const OID_RCMAX_SYS_UPTIME ='.1.3.6.1.2.1.1.3';

    const OID_RCMAX_GPS_X_POS ='.1.3.6.1.4.1.15004.4.10.1.1.5.1.14';
    const OID_RCMAX_GPS_Y_POS ='.1.3.6.1.4.1.15004.4.10.1.1.5.1.15';
    const OID_RCMAX_GPS_Z_POS ='.1.3.6.1.4.1.15004.4.10.1.1.5.1.16';
    const OID_RCMAX_GPS_OPERATION_MODE ='.1.3.6.1.4.1.15004.4.10.1.1.5.1.1';

    const OID_RCMAX_SITE_ID ='.1.3.6.1.4.1.15004.4.10.1.1.1.5.1';
    const OID_RCMAX_ANTENNA_TYPE ='.1.3.6.1.4.1.15004.4.10.1.1.1.5.4';
    const OID_RCMAX_ANTENNA_AZIMUTH ='.1.3.6.1.4.1.15004.4.10.1.1.1.5.5';

    /**
     * Returns the time ticks (100th sec) since BS was started
     *
     * > Time ticks since Asterisk was started.
     *
     * @return int Time ticks since BS was started
     */
    public function uptime() {
        return $this->getSNMP()->get(self::OID_RCMAX_SYS_UPTIME);
    }

    /**
     * Returns the current number of active channels.
     *
     * > Current number of active channels.
     *
     * @return int The current number of active channels.
     */
    public function gps_mode() {
        return $this->getSNMP()->get(self::OID_RCMAX_GPS_OPERATION_MODE);
    }

    /**
     * Returns the number of channel types (technologies) supported.
     *
     * > Number of channel types (technologies) supported.
     *
     * @return int The number of channel types (technologies) supported.
     */
    public function location_lat() {
        return $this->getSNMP()->get(self::OID_RCMAX_GPS_X_POS);
    }

    public function location_long() {
        return $this->getSNMP()->get(self::OID_RCMAX_GPS_Y_POS);
    }

    public function location_height() {
        return $this->getSNMP()->get(self::OID_RCMAX_GPS_Z_POS);
    }
    
        /**
     * Returns the number of channel types (technologies) supported.
     *
     * > Number of channel types (technologies) supported.
     *
     * @return int The number of channel types (technologies) supported.
     */
    public function site_id() {
        return $this->getSNMP()->get(self::OID_RCMAX_SITE_ID);
    }

    public function antenna_azimuth() {
        return $this->getSNMP()->get(self::OID_RCMAX_ANTENNA_AZIMUTH);
    }

    public function antenna_type() {
        return $this->getSNMP()->get(self::OID_RCMAX_ANTENNA_TYPE);
    }

}
