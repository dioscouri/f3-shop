<?php
namespace Shop\Site\Controllers;

class Address extends \Dsc\Controller
{
    /**
     * Gets a list of countries
     */
    public function countries()
    {
        $result = \Shop\Models\Countries::find();
        
        return $this->outputJson( $this->getJsonResponse( array(
            'message' => \Dsc\System::instance()->renderMessages(),
            'result' => $result 
        ) ) );
    }

    /**
     * Gets a list of regions, filtered by a country isocode_2
     */
    public function regions()
    {
        $f3 = \Base::instance();
        $country_isocode_2 = $f3->get('PARAMS.country_isocode_2');
        
        $result = \Shop\Models\Regions::byCountry( $country_isocode_2 );
        
        return $this->outputJson( $this->getJsonResponse( array(
            'message' => \Dsc\System::instance()->renderMessages(),
            'result' => $result 
        ) ) );
    }

    /**
     * Validates an address
     */
    public function validate()
    {
    }
}