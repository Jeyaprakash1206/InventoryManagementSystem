<?php

  class DB
  {
    var $defaultDebug = false;
    var $mtStart;
    var $nbQueries;
    var $lastResult;
    function DB($base, $server, $user, $pass)
    {
     
      $this->mtStart    = $this->getMicroTime();
      $this->nbQueries  = 0;
      $this->lastResult = NULL;
      global $con;
      $con = mysqli_connect($server, $user, $pass) or die('Server connexion not possible.');
      //mysqli_select_db($base)               or die(mysqli_error($base));//die('Database connexion not possible.');
      mysqli_select_db($con, $base) or die(mysqli_error($con));

    }
    function query($con,$query, $debug = -1)
    {
     
      
      $this->nbQueries++;
      $this->lastResult = mysqli_query($con,$query) or $this->debugAndDie($con,$query);
      $this->debug($debug, $query, $this->lastResult);
      return $this->lastResult;
    }
    function execute($con,$query, $debug = -1)
    {
     
      
      $this->nbQueries++;
      mysqli_query($con,$query) or $this->debugAndDie($con,$query);
      $this->debug($debug, $query);
    }
    function fetchNextObject($result = NULL)
    {
     
      if ($result == NULL)
        $result = $this->lastResult;
      if ($result == NULL || mysqli_num_rows($result) < 1)
        return NULL;
      else
        return mysqli_fetch_object($result);
    }
    function numRows($result = NULL)
    {
     
      if ($result == NULL)
        return mysqli_num_rows($this->lastResult);
      else
        return mysqli_num_rows($result);
    }
    function queryUniqueObject($con,$query, $debug = -1)
    {
     
      $query = "$query LIMIT 1";
      $this->nbQueries++;
      $result = mysqli_query($con,$query) or $this->debugAndDie($con,$query);
      $this->debug($debug, $query, $result);
      return mysqli_fetch_object($result);
    }
    function queryUniqueValue($con,$query, $debug = -1)
    {
     
      $query = "$query LIMIT 1";
      $this->nbQueries++;
      $result = mysqli_query($con,$query) or $this->debugAndDie($con,$query);
      $line = mysqli_fetch_row($result);
      $this->debug($debug, $query, $result);
      return $line[0];
    }
    function maxOf($con,$column, $table, $where)
    {
     
      return $this->queryUniqueValue($con,"SELECT MAX(`$column`) FROM `$table` WHERE $where");
    }
    function maxOfAll($con,$column, $table)
    {
     
      return $this->queryUniqueValue($con,"SELECT MAX(`$column`) FROM `$table`");
    }
    function countOf($con,$table, $where)
    {
     
      return $this->queryUniqueValue($con,"SELECT COUNT(*) FROM `$table` WHERE $where");
    }
    function countOfAll($con,$table)
    {
     
      return $this->queryUniqueValue($con,"SELECT COUNT(*) FROM `$table`");
    }
    function debugAndDie($con,$query)
    {
     
      $this->debugquery($query, "Error");
      die("<p style=\"margin: 2px;\">".mysqli_error($con)."</p></div>");
    }
    function debug($debug, $query, $result = NULL)
    {
     
      if ($debug === -1 && $this->defaultDebug === false)
        return;
      if ($debug === false)
        return;
      $reason = ($debug === -1 ? "Default Debug" : "Debug");
      $this->debugquery($query, $reason);
      if ($result == NULL)
        echo "<p style=\"margin: 2px;\">Number of affected rows: ".mysqli_affected_rows()."</p></div>";
      else
        $this->debugResult($result);
    }
    function debugquery($query, $reason = "Debug")
    {
     
      $color = ($reason == "Error" ? "red" : "orange");
      echo "<div style=\"border: solid $color 1px; margin: 2px;\">".
           "<p style=\"margin: 0 0 2px 0; padding: 0; background-color: #DDF;\">".
           "<strong style=\"padding: 0 3px; background-color: $color; color: white;\">$reason:</strong> ".
           "<span style=\"font-family: monospace;\">".htmlentities($query)."</span></p>";
    }
    function debugResult($result)
    {
     
      echo "<table border=\"1\" style=\"margin: 2px;\">".
           "<thead style=\"font-size: 80%\">";
      $numFields = mysqli_num_fields($result);
      $tables    = array();
      $nbTables  = -1;
      $lastTable = "";
      $fields    = array();
      $nbFields  = -1;
      while ($column = mysqli_fetch_field($result)) {
        if ($column->table != $lastTable) {
          $nbTables++;
          $tables[$nbTables] = array("name" => $column->table, "count" => 1);
        } else
          $tables[$nbTables]["count"]++;
        $lastTable = $column->table;
        $nbFields++;
        $fields[$nbFields] = $column->name;
      }
      for ($i = 0; $i <= $nbTables; $i++)
        echo "<th colspan=".$tables[$i]["count"].">".$tables[$i]["name"]."</th>";
      echo "</thead>";
      echo "<thead style=\"font-size: 80%\">";
      for ($i = 0; $i <= $nbFields; $i++)
        echo "<th>".$fields[$i]."</th>";
      echo "</thead>";
      while ($row = mysqli_fetch_array($result)) {
        echo "<tr>";
        for ($i = 0; $i < $numFields; $i++)
          echo "<td>".htmlentities($row[$i])."</td>";
        echo "</tr>";
      }
      echo "</table></div>";
      $this->resetFetch($result);
    }
    function getExecTime()
    {
     
      return round(($this->getMicroTime() - $this->mtStart) * 1000) / 1000;
    }
    function getQueriesCount()
    {
     
      return $this->nbQueries;
    }
    function resetFetch($result)
    {
     
      if (mysqli_num_rows($result) > 0)
        mysqli_data_seek($result, 0);
    }
    function lastInsertedId()
    {
     
      return mysqli_insert_id();
    }
    function close()
    {
     
      mysqli_close();
    }
    function getMicroTime()
    {
      list($msec, $sec) = explode(' ', microtime());
      return floor($sec / 1000) + $msec;
    }
  }
?>
