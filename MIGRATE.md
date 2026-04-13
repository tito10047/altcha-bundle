# Migration Guide: 1.0 to 2.0

This guide outlines the changes required to migrate from version 1.0 to 2.0 of the `altcha-bundle`.

## JavaScript Changes

The main JavaScript i18n file has been moved and updated.

- **Old path:** `"altcha/dist/altcha.i18n.js": "^2.0.0"`
- **New path:** `"altcha/dist/main/altcha.i18n.js": "^3.0.2"`

### Webpack Encore Update
If you are using a custom alias in `webpack.config.js`, update it as follows:

```js
// Before
module.exports.resolve.alias["altcha/dist/altcha.i18n.js"] = 'altcha/i18n';

// After
module.exports.resolve.alias["altcha/dist/i18n/all.js"] = 'altcha/i18n';
```

## Configuration

The configuration remains mostly backward compatible, but it is recommended to update to the new keys.

| Old Key | New Key | Status |
|---------|---------|--------|
| `hmacKey` | `hmacSignature` | Deprecated (fallback exists) |
| `max_number` | - | Deprecated (replaced by `cost` and `counter_max`) |

### New Configuration Options
- `hmacAlgorithm`: Specify the HMAC algorithm (default: `SHA-256`).
- `hmacKeySignature`: Optional signature key.
- `cost`: Challenge difficulty cost (default: `5000`).
- `counter_min`: Minimum counter value (default: `5000`).
- `counter_max`: Maximum counter value (default: `10000`).
- `timeout`: Challenge timeout in seconds (default: `30.0`).

## Code Structure Changes

The internal architecture has been refactored to support version 2.0 of the `altcha-org/altcha` PHP library.

### Core Changes in `src/`
- **Dependency Injection:**
    - `AltchaExtension` now handles `hmacSignature` instead of `hmacKey`.
- **Services:**
    - `ChallengeResolver`: Now requires `DriverKeyProviderInterface` and handles new configuration parameters (cost, counter, timeout).
    - `AltchaValidator` and `AltchaSentinelValidator`: Refactored to use the new verification logic based on `Payload` and `VerifySolutionOptions`.
- **New Services & Interfaces:**
    - `DriverKeyProviderInterface` / `DriverKeyProvider`: Centralizes algorithm selection.
    - `SolveChallengeResolverInterface` / `SolveChallengeResolver`: New service for verifying challenges.
- **Form Type:**
    - `AltchaType`: Added options for `cost`, `counter_min`, `counter_max`, and `timeout`. `max_number` is now deprecated.

### Library Update
The bundle now uses `altcha-org/altcha` v2. Key classes like `ChallengeOptions` have been replaced by `CreateChallengeOptions` or `VerifySolutionOptions`.
