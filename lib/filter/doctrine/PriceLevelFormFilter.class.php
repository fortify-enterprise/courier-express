<?php

/**
 * PriceLevel filter form.
 *
 * @package    cexp
 * @subpackage filter
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PriceLevelFormFilter extends BasePriceLevelFormFilter
{
  public function configure()
  {
  }


  public function doBuildQuery(array $values)
  {
    $courier_id = sfCOntext::getInstance()->getUser()->getAttribute('courier_id');

    // build query

    $query = parent::doBuildQuery($values);

    if ($courier_id)
    {
      // add join to price level and filter by courier

      //$query->where("r.InvoiceProduct ip")
      //  ->innerJoin("ip.Product p");

      //if ($sku != "")
      //  $query->addWhere("p.sku = ?", $sku);
      $query->addWhere("r.courier_id = ?", $courier_id);
    }
    print $query;
    return $query;
  }
}
