<?php

class CountryPeer extends BaseCountryPeer
{
     static public function getSortedCountries() {
        $c = new Criteria();
        $c->addAnd(CountryPeer::CALLING_CODE,sfConfig::get('app_country_code'));
        $c->addAscendingOrderByColumn(CountryPeer::NAME);
        $rs = CountryPeer::doSelect($c);
        return $rs;
    }
}
