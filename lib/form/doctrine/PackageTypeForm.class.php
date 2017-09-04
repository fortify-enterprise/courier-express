<?php

/**
 * PackageType form.
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */

class PackageTypeForm extends BasePackageTypeForm
{
  public function configure()
  {
    // get the pacakge types
    $packages_db   = new Packages_Db();
    $package_types = $packages_db->get_package_types();

    $this->setWidgets(array(
      'id' => new sfWidgetFormSelect(array('choices' => $package_types), array('tabindex' => 1, 'style' => 'width: 120px; margin-right: 10px')),
    ));

    $this->widgetSchema->setLabels(array(
      'id' => 'Package type',
    ));

    $this->getWidgetSchema()->setHelps(array(
      'id' => 'Package type',
    ));

    $this->setValidators(array(

      'id' => new sfValidatorChoice(array('choices' => array_keys($package_types))),

    ));

    $this->widgetSchema->setNameFormat('package_type[%s]');
    $this->widgetSchema->setFormFormatterName('Vertical');

		foreach( $this->validatorSchema->getFields() as $field){
   		$field->setOption('trim', true);
		}

  }
}
