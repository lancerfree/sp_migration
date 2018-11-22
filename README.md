# Module sp_migration

This module contains four migrations:  
   **sp_migration_automobilies** - Read xml file and put data in the vocabulary.  
   **sp_migration_categories_term** - Read csv file directory with images and creates terms with images.
 This migration is required by *sp_migration_categories_url_alias* migration  
 **sp_migration_categories_url_alias** - This migration creates aliases for term created 
 by previous migration.  
  **sp_migration_export_news** - Exports nodes type of news with tags and images to specific images folder 
  and to the another database.    
  All migration support rollback.They can be executed on the path 'migration/{migration_name}/{migration_direction}'(ex. migration/sp_migration_categories_term/rollback)
  
  