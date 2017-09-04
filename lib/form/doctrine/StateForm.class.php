<?php

/**
 * State form.
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class StateForm extends BaseStateForm
{
  public function configure()
  {
    $address_db  = new Address_Db();
    $state_names = $address_db->get_states_list(true);

    $this->setWidgets(array(
      'id'  => new sfWidgetFormSelect(array('choices' => $state_names),
      array('style' => 'width: 250px')),
      'name' => new sfWidgetFormInputText(array(), array('style' => 'width: 120px')),
      'alpha_code' => new sfWidgetFormInputText(array(), array('style' => 'width: 50px')),
      'abbreviation' => new sfWidgetFormInputText(array(), array('style' => 'width: 50px')),
      'numerical_code' => new sfWidgetFormInputText(array(), array('style' => 'width: 100px')),
    ));

    $this->widgetSchema->setLabels(array(
      'id' => 'Name of state',
      'name'   => 'Name of state',
      'alpha_code'   => 'State alpha code',
      'abbreviation' => 'State abbreviation',
      'numerical_code' => 'State numerical code',
    ));

    $this->getWidgetSchema()->setHelps(array(
      'id' => 'Name of state',
      'name'   => 'Name of state',
      'alpha_code'   => 'State alpha code',
      'abbreviation' => 'State abbreviation',
      'numerical_code' => 'State numerical code',
    ));

    $this->setValidators(array(

      'id' => new sfValidatorChoice(array('choices' => array_keys($state_names))),

      'name' => new sfValidatorString(array('min_length' => 2, 'max_length' => 45), array(
        'required'   => 'State is required',
        'min_length' => 'Province must be at least %min_length% characters.',
        'max_length' => 'Province must not exceed %max_length% characters.',
      )),

      'alpha_code' => new sfValidatorString(array('min_length' => 2, 'max_length' => 2), array(
        'required'   => 'Alpha code is required',
        'min_length' => 'Sgc number must be at least %min_length% characters.',
        'max_length' => 'Sgc number must not exceed %max_length% characters.',
      )),

      'abbreviation' => new sfValidatorString(array('min_length' => 2, 'max_length' => 50), array(
        'required'   => 'Alpha code is required',
        'min_length' => 'Alpha code must be at least %min_length% characters.',
        'max_length' => 'Alpha code must not exceed %max_length% characters.',
      )),

      'numerical_code' => new sfValidatorString(array('min_length' => 30, 'max_length' => 300), array(
        'required'   => 'Abbreviation is required',
        'min_length' => 'Abbreviation must be at least %min_length% characters.',
        'max_length' => 'Abbreviation must not exceed %max_length% characters.',
      )),

    ));

		$this->widgetSchema->setFormFormatterName('Vertical');
  }


  protected function doSave($con = null)
  {
  }

}
