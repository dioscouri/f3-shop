<?php
namespace Shop\Models;

class Currencies extends \Dsc\Mongo\Collection
{
    use \Dsc\Traits\Models\Publishable;
    use \Dsc\Traits\Models\ForSelection;

    protected $__collection_name = 'shop.currencies';
    protected $__type = 'currency';    
    
    public $code = null;
    public $title = null;
    
    /**
     * Instantiate class, optionally binding it with an array/object
     *
     * @param unknown $source
     * @param unknown $options
     */
    public function __construct( $source = array(), $options = array() )
    {
        parent::__construct($source, $options);
        
        $this->__select2_fields['id'] = 'code';
    }    
    
    public static function refresh($force=false)
    {
        $settings = \Shop\Models\Settings::fetch();
        if (empty($settings->{'currency.openexchangerates_api_id'}))
        {
            static::log('Could not refresh list of valid currencies.  Please provide an open exchange rates API ID in the Shop Configuration page', 'ERROR');
            return false;
        }
        
        // once a day is enough
        if (!empty($settings->currencies_last_refreshed) && $settings->currencies_last_refreshed > time() - 24*60 && empty($force))
        {
            return false;
        }
        
        $oer = new \Shop\Lib\OpenExchangeRates( $settings->{'currency.openexchangerates_api_id'} );
        if ($response = $oer->currencies())
        {
            if ($currencies = \Joomla\Utilities\ArrayHelper::fromObject($response))
            {
                foreach ($currencies as $code=>$title)
                {
                    $currency = (new static)->setParam('conditions', array(
                        'code' => $code
                    ))->getItem();
        
                    if (empty($currency->id)) {
                        $currency = new static;
                    }
        
                    $currency->code = $code;
                    $currency->title = $title;
                    $currency->store();
                }
                
                $settings->currencies_last_refreshed = time();
                $settings->save();
            }
        }

        return true;
    }

}