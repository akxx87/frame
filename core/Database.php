<?php

namespace app\core;

class Database
{

    public \PDO $pdo;

    /**
     *  Database constructor
     */
    public function __construct(array $config)
    {
        $host = $config['host'] ?? '';
        $user = $config['user'] ?? '';
        $pass = $config['pass'] ?? '';
        $this->pdo = new \PDO($host, $user, $pass);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    }

    public function applyMigrations()
    {

        $this->createTable();
       $applemigrations = $this->getapplemigrations();

       $newmigrations = [];

        $files = scandir(Application::$ROOT_DIR.'/migrations');
       $toApplyMigrations= array_diff($files, $applemigrations);
        foreach ($toApplyMigrations as $migration)
        {
            if($migration === '.' || $migration === '..')
            {
                continue;
            }

            require_once Application::$ROOT_DIR.'/migrations/'.$migration;
            $pat = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $pat();
            $this->log("Applying migrations $migration");
            $instance -> up();
            $this->log("Applied migrations $migration");
            $newmigrations[] = $migration;

        }
        if (!empty($newmigrations))
        {
            $this->saveMigrations($newmigrations);
        }else
        {
             $this->log("all migration are applied");
        }

    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    public function saveMigrations(array $migrations)
    {
        $str = $migrations = implode(",",  array_map(fn($m) => "('$m')" ,$migrations));
       $statement =  $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $str");
        $statement->execute();
    }

    public function createTable()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations(
    id INT AUTO_INCREMENT PRIMARY KEY,
    migration VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)
    ENGINE=InnoDB;");

    }

    public function getapplemigrations()
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    protected function log ($message)
    {
        echo '['.date('Y-m-d H:i:s').'] - '. $message.PHP_EOL;
    }
}