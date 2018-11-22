<?php

namespace Drupal\sp_migration\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\migrate\MigrateException;
use Drupal\migrate\MigrateMessage;
use Drupal\migrate\MigrateExecutable;
use Drupal\migrate\Plugin\MigrationPluginManager;


/**
 * Controller for migration
 */
class StartMigrationController extends ControllerBase {

  /**
   * Migration Manager
   *
   * @var MigrationPluginManager
   */
  protected $migrationManager;

  /*
   * Route callback
   *
   * @param string $migration_name
   *   Name of migrations
   * @param string $migration_direction
   *   Name of diraction install or rollback
   *
   * @throw MigrateException
   */
  public function startMigration($migration_name, $migration_direction) {
    $migration = $this->migrationManager->createInstance($migration_name);
    if (!$migration) {
      throw new MigrateException("No this migration to call");
    }
    $executable = new MigrateExecutable($migration, new MigrateMessage());

    if ($migration_direction === 'rollback') {
      $executable->rollback();
    }
    elseif ($migration_direction === 'install') {
      $executable->import();
    }
    else {
      throw new MigrateException("This direction not exists!");
    }
    return [
      '#markup' => $this->t('Migration @migration_name @migration_direction !',
        [
          '@migration_name' => $migration_name,
          '@migration_direction' => $migration_direction,
        ]),
    ];

  }

  /**
   * Setup all requirement property.
   *
   * @param MigrationPluginManager $migration_manager
   *   Migration manager for use.
   */
  public function __construct(MigrationPluginManager $migration_manager) {
    $this->migrationManager = $migration_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.migration')
    );
  }

}