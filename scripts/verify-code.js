import { execSync } from 'child_process';

/* global console, process */

try {
    // Vérifier ESLint sur les fichiers JavaScript du projet uniquement
    console.log('🔍 Vérification ESLint...');
    execSync('npm run lint', { stdio: 'inherit' });

    // Vérifier PHPCS sur les fichiers PHP du projet uniquement
    console.log('\n🔍 Vérification PHPCS...');
    execSync('docker-compose exec -T php ./vendor/bin/phpcs src/', { stdio: 'inherit' });

    // Vérifier PHPStan sur les fichiers PHP du projet uniquement
    console.log('\n🔍 Vérification PHPStan...');
    execSync('docker-compose exec -T php ./vendor/bin/phpstan analyse src/', { stdio: 'inherit' });

    console.log('\n✅ Toutes les vérifications sont passées !');
    process.exit(0);
} catch {
    console.error('\n❌ Des erreurs ont été trouvées. Veuillez les corriger avant de continuer.');
    process.exit(1);
}
