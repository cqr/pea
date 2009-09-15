<?php
$_pea_dbConfig = Spyc::YAMLLoad(CONFIGDIR.'/database.yml');
$_pea_dbConnector = new MySQLConnector(
    $_pea_dbConfig[$_pea_dbConfig['use']]['hostname'],
    $_pea_dbConfig[$_pea_dbConfig['use']]['username'],
    $_pea_dbConfig[$_pea_dbConfig['use']]['password'],
    $_pea_dbConfig[$_pea_dbConfig['use']]['database']
    );
unset($_pea_dbConfig);