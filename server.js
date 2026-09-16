const { execSync, spawn } = require('child_process');
const port = process.env.PORT || 10000;

console.log('Installing PHP...');
try {
  execSync('apt-get update -qq && apt-get install -y -qq php8.2 php8.2-mysql php8.2-pdo', { stdio: 'inherit' });
} catch (e) {
  try {
    execSync('apt-get update -qq && apt-get install -y -qq php php-mysql', { stdio: 'inherit' });
  } catch (e2) {
    console.error('PHP install failed:', e2.message);
    process.exit(1);
  }
}

console.log(`Starting PHP server on port ${port}...`);
const php = spawn('php', ['-S', `0.0.0.0:${port}`, '-t', '.'], { stdio: 'inherit' });

php.on('exit', (code) => {
  console.log(`PHP exited with code ${code}`);
  process.exit(code);
});
