<?php


class CreditCardTypeTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('CreditCardType');
    }
}