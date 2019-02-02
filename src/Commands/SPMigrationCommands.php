<?php
namespace Drupal\sp_migration\Commands;


use Drush\Commands\DrushCommands;
use Drupal\migrate\MigrateException;
use Drupal\migrate\MigrateMessage;
use Drupal\migrate\MigrateExecutable;
use Drush\Utils\StringUtils;


/**
 * Class for sp_migration module drush commands.
 */
class SPMigrationCommands extends DrushCommands {

  /**
   * List available migration for current module.
   *
   * @var array
   */
  protected $availableMigrations = [
    'sp_migration_automobilies',
    'sp_migration_categories_term',
    'sp_migration_categories_url_alias',
    'sp_migration_export_news',
    ];

  /**
   * Executes selected migration or all.
   *
   * @command sp_migration:migrate
   * @param $migrations A comma-separated list of migration names.
   * @aliases spm
   * @usage drush spm
   *   Executes all migration.
   * @throws
   * @return string
   */
  public function migrate($migrations = '') {
    $migrations = StringUtils::csvToArray($migrations);
    /** @var \Drupal\migrate\Plugin\MigrationPluginManager $migration_manager */
    $migration_manager = \Drupal::service('plugin.manager.migration');

    $list_migration= (count($migrations))?$migrations:$this->availableMigrations;
    foreach ($list_migration as $lm_value) {
      $migration = $migration_manager->createInstance($lm_value);
      if (!$migration) {
        throw new MigrateException("The migration with id $migration not exists!");
      }
      $executable = new MigrateExecutable($migration, new MigrateMessage());
      $executable->import();
    }

    return 'Migrations executed!';
  }
}