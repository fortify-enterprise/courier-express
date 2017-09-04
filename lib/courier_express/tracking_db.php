<?php

class Tracking_Db extends Base_Lib
{
  // if found then call function to get all info based on payment code

  // if not found
  // search the packages table for this code
  // if found then get the payment code and call function to get all based on payment code

  // else
  // return nothing

  // get the packages for the payments code


  function get_tracking_info($code)
  {
    // serach in the _payments table for this code
    $q = Doctrine_Query::create()
         ->select('p.id')
         ->from('Payment p')
         ->where('p.payment_code = ?', $code);

    $payments_count = $q->execute()->count();
    if ($payments_count == 1)
      return $this->get_payments_code_info($code);


     $r = Doctrine_Query::create()
         ->select('pt.payment_code')
         ->addSelect('p.package_code')
         ->addSelect('pt.id')
         ->addSelect('pp.payment_id')
         ->from('Package p')
         ->leftJoin('p.PackagePayment pp')
         ->leftJoin('pp.Payment pt')
         ->where('p.package_code = ?', $code)
         ->fetchOne();

    $payment_code = isset($r['PackagePayment'][0]['Payment']['payment_code']['payment_code']) ?
      $r['PackagePayment'][0]['Payment']['payment_code'] : null;

    if ($payment_code)
      return $this->get_payments_code_info($payment_code);

    return null;
  }


  function get_payments_code_info ($payment_code)
  {
    $q = Doctrine_Query::create()
         ->select('p.package_code as package_code')
         ->addSelect('p.from_address_id')
         ->addSelect('p.to_address_id')
         ->addSelect('st.status as status')
         ->addSelect('pd.signed_by')
         ->addSelect('p.id')
         ->from('Package p')
         ->leftJoin('p.PackagePayment pp')
         ->leftJoin('pp.Payment ps')
         ->leftJoin('p.PackageDetail pd')
         ->leftJoin('p.PackageStatus st')
         ->where('ps.payment_code = ?', $payment_code)
         ->fetchArray();
    if ($q)
    {
      $address_db = new Address_Db();
      foreach ($q as $i => $package)
      {
        $q[$i]['from_address'] = $address_db->get_obj_text_address($package['from_address_id']);
        $q[$i]['to_address']   = $address_db->get_obj_text_address($package['to_address_id']);
      }
      return $q;
    }

  	return null;
	}
}
