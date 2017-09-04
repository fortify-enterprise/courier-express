<?php

class sfWidgetFormSchemaFormatterVertical extends sfWidgetFormSchemaFormatter
{
  protected
    $rowFormat       = "
													<div class='vertical_form_row%row_class%'>\n
                        		%label% \n
														<a style='text-decoration: none' title='%help%' class='tip' href='#'>[?]</a><br /> \n
														%field% \n
														%error% \n
                        		%hidden_fields%\n
													</div>
												",

    $errorRowFormat  				= "<div>%errors%</div>",
		$errorListFormatInARow  = "<ul class=\"error_list\">\n%errors%</ul>\n",
    $helpFormat      				= '%help%',
    $decoratorFormat 				= "<div>\n  %content%</div>";

	// ...
 
  public function formatRow($label, $field, $errors = array(), $help = '', $hiddenFields = null)
  {
    $row = parent::formatRow(
      $label,
      $field,
      $errors,
      $help,
      $hiddenFields
    );
 
    return strtr($row, array(
      '%row_class%' => (count($errors) > 0) ? ' form_row_error' : '',
    ));
  }
}
