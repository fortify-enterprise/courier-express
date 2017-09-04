<?php

/**
 * Province form.
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ProvinceForm extends BaseProvinceForm
{
  public function configure()
  {
    $address_db     = new Address_Db();
    $province_names = $address_db->get_provinces_list(true);
    $province = $this->getObject();
    $default_id = $province['id'] ? $province['id'] : 1;

    $this->setWidgets(array(
      'id'  => new sfWidgetFormSelect(array('choices' => $province_names, 'default' => $default_id),
      array('style' => 'width: 250px')),
      'province_territory' => new sfWidgetFormInputText(array(), array('style' => 'width: 120px')),
      'sgc_code' => new sfWidgetFormInputText(array(), array('style' => 'width: 50px')),
      'alpha_code' => new sfWidgetFormInputText(array(), array('style' => 'width: 50px')),
      'abbreviation' => new sfWidgetFormInputText(array(), array('style' => 'width: 100px')),
    ));

    $this->widgetSchema->setLabels(array(
      'id' => 'Province or terretory',
      'province_territory'   => 'Province terretory',
      'sgc_code'   => 'Sgc code',
      'alpha_code' => 'Alpha code',
      'abbreviation' => 'Province abbreviation',
    ));

    $this->getWidgetSchema()->setHelps(array(
      'id' => 'Province or terretory',
      'province_territory'   => 'Province terretory',
      'sgc_code' => 'Province numeric code',
      'alpha_code' => 'Province alpha code',
      'abbreviation' => 'Province abbreviation',
    ));

    $this->setValidators(array(

      'id' => new sfValidatorChoice(array('choices' => array_keys($province_names))),

      'province_territory' => new sfValidatorString(array('min_length' => 2, 'max_length' => 45), array(
        'required'   => 'Province is required',
        'min_length' => 'Province must be at least %min_length% characters.',
        'max_length' => 'Province must not exceed %max_length% characters.',
      )),

      'sgc_code' => new sfValidatorString(array('min_length' => 2, 'max_length' => 2), array(
        'required'   => 'Sgc is required',
        'min_length' => 'Sgc number must be at least %min_length% characters.',
        'max_length' => 'Sgc number must not exceed %max_length% characters.',
      )),

      'alpha_code' => new sfValidatorString(array('min_length' => 2, 'max_length' => 50), array(
        'required'   => 'Alpha code is required',
        'min_length' => 'Alpha code must be at least %min_length% characters.',
        'max_length' => 'Alpha code must not exceed %max_length% characters.',
      )),

      'abbreviation' => new sfValidatorString(array('min_length' => 30, 'max_length' => 300), array(
        'required'   => 'Abbreviation is required',
        'min_length' => 'Abbreviation must be at least %min_length% characters.',
        'max_length' => 'Abbreviation must not exceed %max_length% characters.',
      )),

    ));

		$this->widgetSchema->setFormFormatterName('Vertical');

		foreach( $this->validatorSchema->getFields() as $field){
   		$field->setOption('trim', true);
		}
  }


  protected function doSave($con = null)
  {
  }
}
