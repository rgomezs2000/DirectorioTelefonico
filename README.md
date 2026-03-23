# DirectorioTelefonico

## CI/CD Windows (GitHub Actions)

El workflow de despliegue está en `.github/workflows/php-cd.yml`.

### ¿Dónde se configuran los secrets?
En GitHub, dentro del repositorio:

1. **Settings**
2. **Secrets and variables**
3. **Actions**
4. **New repository secret**

Crea estos secrets (los mismos que invoca el workflow):

- `WINDOWS_HOST` (ejemplo: `ec2-15-228-74-115.sa-east-1.compute.amazonaws.com`)
- `WINDOWS_PORT` (opcional, por defecto `22`)
- `WINDOWS_USER` (ejemplo: `Administrator`)
- `WINDOWS_PASSWORD`
- `WINDOWS_DEPLOY_PATH` (ejemplo: `C:\xampp\htdocs\DirectorioTelefonico`)
