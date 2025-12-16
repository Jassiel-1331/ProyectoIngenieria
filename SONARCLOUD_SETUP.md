# SonarCloud Configuration Guide

## Overview

SonarCloud is integrated into your CI/CD pipeline to perform code quality and security analysis on every push and pull request to the repository.

## Configuration Files

### 1. `sonar-project.properties` (Project Root)

Contains the minimal SonarCloud configuration:

```properties
sonar.projectKey=Jassiel-1331_ProyectoIngenieria
sonar.organization=jassiel-1331
sonar.host.url=https://sonarcloud.io
sonar.projectName=ProyectoIngenieria
sonar.projectVersion=1.0
sonar.sources=app,config,routes
sonar.tests=tests
sonar.test.inclusions=tests/**
sonar.exclusions=vendor/**,node_modules/**,storage/**,bootstrap/cache/**,public/**
```

**Key settings:**
- `projectKey` & `organization`: Identifies your project in SonarCloud.
- `sources`: Directories to analyze (PHP code).
- `tests`: Location of test files.
- `exclusions`: Directories to skip (vendor, dependencies, cache, etc.).

### 2. `.github/workflows/sonarcloud.yml` (CI Pipeline)

GitHub Actions workflow that runs SonarCloud analysis on push and pull requests:

```yaml
name: SonarCloud
on:
  push:
    branches: [ main, master ]
  pull_request:
jobs:
  sonar:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
        with:
          fetch-depth: 0
      - uses: actions/setup-java@v4
        with:
          distribution: 'temurin'
          java-version: '17'
      - run: composer install --no-interaction --no-progress --prefer-dist
      - uses: SonarSource/sonarcloud-github-action@master
        with:
          args: -Dsonar.login=${{ secrets.SONAR_TOKEN }}
        env:
          GITHUB_TOKEN: ${{ secrets.GITHUB_TOKEN }}
          SONAR_TOKEN: ${{ secrets.SONAR_TOKEN }}
```

**Workflow behavior:**
- Triggers on every push to `main` or `master` branches.
- Triggers on every pull request.
- Installs Java (required by SonarCloud Scanner).
- Installs PHP dependencies via Composer.
- Runs the SonarCloud analysis using the `SONAR_TOKEN` secret.

## Setup Requirements

### 1. SonarCloud Project Created

Ensure your project exists at: `https://sonarcloud.io/projects/Jassiel-1331_ProyectoIngenieria`

### 2. GitHub Secret: `SONAR_TOKEN`

The workflow requires a SonarCloud token stored as a GitHub secret:

1. Go to your **GitHub repository** → **Settings** → **Secrets and variables** → **Actions**
2. Click **New repository secret**
3. Name: `SONAR_TOKEN`
4. Value: Your SonarCloud token (from `https://sonarcloud.io/account/security`)
5. Click **Add secret**

### 3. Workflow Permissions

Ensure your workflow has proper permissions:

1. Go to **Settings** → **Actions** → **General**
2. Under "Workflow permissions", select **Read and write permissions**
3. Save

## Testing Locally

To test SonarCloud analysis locally (optional):

```bash
# Install SonarScanner
# Refer to: https://docs.sonarcloud.io/advanced-setup/ci-cd-integration/

# Run analysis (replace YOUR_TOKEN)
sonar-scanner \
  -Dsonar.projectKey=Jassiel-1331_ProyectoIngenieria \
  -Dsonar.organization=jassiel-1331 \
  -Dsonar.sources=app,config,routes \
  -Dsonar.host.url=https://sonarcloud.io \
  -Dsonar.login=YOUR_TOKEN
```

## Expected Behavior

- **On PR**: SonarCloud checks code against your quality gate. If it fails, the PR shows status checks.
- **On Merge to main**: Full analysis runs; results are visible in SonarCloud dashboard.
- **Quality Gate**: Configure at `https://sonarcloud.io/project/settings?id=Jassiel-1331_ProyectoIngenieria`

## Troubleshooting

| Issue | Solution |
|-------|----------|
| Job fails with "test directory not found" | Ensure `tests/Unit` and `tests/Feature` directories exist (with `.gitkeep` files). |
| "SONAR_TOKEN not set" | Verify the secret is added in GitHub and the workflow step references it. |
| Analysis not triggering | Check that the workflow `.yml` file is in `.github/workflows/` and committed to the repo. |
| Build/test failures before analysis | Fix errors in `.github/workflows/tests.yml` (PHP, Composer, phpunit issues). |

## References

- [SonarCloud Documentation](https://docs.sonarcloud.io/)
- [GitHub Actions Integration](https://docs.sonarcloud.io/advanced-setup/ci-cd-integration/github-actions/)
- Project Dashboard: `https://sonarcloud.io/projects/Jassiel-1331_ProyectoIngenieria`
