<?php

use Spatie\SchemaOrg\Schema;

$organizationSchema = Schema::organization();

// Iterate over properties defined in config.php and build up the structured data for output.
foreach ($config_schema as $schema_property => $schema_property_value) {
    if($schema_property_value) {
        $organizationSchema->$schema_property($schema_property_value);
    }
}


if($organizationSchema) {
    echo $organizationSchema->toScript();
}
