<?php

/**
 * CourierContact form.
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CourierContactForm extends BaseCourierContactForm
{
  public function configure()
  {
		foreach( $this->validatorSchema->getFields() as $field){
   		$field->setOption('trim', true);
		}
  }
}
