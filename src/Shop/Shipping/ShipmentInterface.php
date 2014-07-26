<?php
/**
 * The shipper interface is for all shipping vendor classes.
 */
namespace Shop\Shipping;

interface ShipmentInterface
{

    public function setShipment(\Shop\Shipping\Shipment $Shipment);

    public function setConfig(array $config);

    public function getRate();

    public function createLabel();

    public function validForCart();
}
