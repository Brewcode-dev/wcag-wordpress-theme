#!/usr/bin/env bash
#
# Bootstraps WordPress inside the running docker-compose stack and activates the WCAG WP theme.
# Idempotent: re-running is safe.

set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

wp() {
	docker compose exec -T wpcli wp --path=/var/www/html --allow-root "$@"
}

echo "[bootstrap] Waiting for database…"
for i in {1..40}; do
	if docker compose exec -T db sh -c 'mariadb-admin ping -uroot -prootpass --silent' >/dev/null 2>&1; then
		break
	fi
	sleep 1
done

echo "[bootstrap] Waiting for WordPress core files…"
for i in {1..40}; do
	if docker compose exec -T wpcli test -f /var/www/html/wp-load.php; then
		break
	fi
	sleep 1
done

if ! wp core is-installed >/dev/null 2>&1; then
	echo "[bootstrap] Installing WordPress…"
	wp core install \
		--url="http://localhost:8088" \
		--title="WCAG WP demo" \
		--admin_user=admin \
		--admin_password=admin \
		--admin_email=admin@example.com \
		--skip-email
else
	echo "[bootstrap] WordPress already installed — skipping installer."
fi

echo "[bootstrap] Activating theme wcag-wp…"
wp theme activate wcag-wp

echo "[bootstrap] (Optional) Installing Elementor…"
wp plugin install elementor --activate || true

echo "[bootstrap] Done. Visit http://localhost:8088 (admin / admin)."
