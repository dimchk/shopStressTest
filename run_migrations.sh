#!/bin/bash

# Loop 10 times
for i in {1..99}; do
    echo "Running migration command iteration $i"
    php bin/console doctrine:fixtures:load --group=OrderFixtures --append --no-interaction
    echo "Migration command iteration $i completed"
done
