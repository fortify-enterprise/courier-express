<?php

class Search_Model extends Base_Lib
{
  function list_countries($limit = '10')
  {
    return $this->db->query("SELECT name FROM countries LIMIT $limit");
  }
}
