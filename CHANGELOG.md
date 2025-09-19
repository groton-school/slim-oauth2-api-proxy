<!--- BEGIN HEADER -->
# Changelog

All notable changes to this project will be documented in this file.
<!--- END HEADER -->

## [3.1.0](https://github.com/groton-school/slim-oauth2-api-proxy/compare/v3.0.1...v3.1.0) (2025-09-19)

### Features

* Inject inner middleware into routes ([44cc5c](https://github.com/groton-school/slim-oauth2-api-proxy/commit/44cc5ce2faa02c74dd8e8f8c94854c9178bede03))


---

## [3.0.1](https://github.com/groton-school/slim-oauth2-api-proxy/compare/v3.0.0...v3.0.1) (2025-09-18)

### Bug Fixes

* Upgrade RequestInterface to slim/http ServerRequest ([2a1149](https://github.com/groton-school/slim-oauth2-api-proxy/commit/2a11495befb43fa8d4455e6f8b955b2093badcc0))


---

## [3.0.0](https://github.com/groton-school/slim-oauth2-api-proxy/compare/v2.0.0...v3.0.0) (2025-09-18)

### ⚠ BREAKING CHANGES

* Replace AccessTokenFactory with AbstractAccessTokenRepository ([bcde0b](https://github.com/groton-school/slim-oauth2-api-proxy/commit/bcde0b235995fb1d761e285c5426bf5bbd322324))


---

## [2.0.0](https://github.com/groton-school/slim-oauth2-api-proxy/compare/v1.0.3...v2.0.0) (2025-09-09)

### ⚠ BREAKING CHANGES

* Support constructor injection by making static members dynamic ([96b9b7](https://github.com/groton-school/slim-oauth2-api-proxy/commit/96b9b769940896ff0887283345767c17e44947ae))


---

## [1.0.3](https://github.com/groton-school/slim-oauth2-api-proxy/compare/v1.0.2...v1.0.3) (2025-09-09)

### Bug Fixes

* Cookie name defaults to slug-tokens ([483e66](https://github.com/groton-school/slim-oauth2-api-proxy/commit/483e66b87c5d448a35cb9ce30de5c90f83d72437))


---

## [1.0.2](https://github.com/groton-school/slim-oauth2-api-proxy/compare/v1.0.1...v1.0.2) (2025-09-08)

### Bug Fixes

* Include search query in proxied request ([e59c2c](https://github.com/groton-school/slim-oauth2-api-proxy/commit/e59c2cf659d060a4d5a5efee7e2843ace7471a3e))


---

## [1.0.1](https://github.com/groton-school/slim-oauth2-api-proxy/compare/v1.0.0...v1.0.1) (2025-09-04)

### Bug Fixes

* Upgrade to dist version of dflydev/fig-cookies ([97797a](https://github.com/groton-school/slim-oauth2-api-proxy/commit/97797a7fe0a58a1a7aa00ff1e877e98f727b0bd1))


---

## [1.0.0](https://github.com/groton-school/slim-oauth2-api-proxy/compare/be1a4343219c181291b20f67119f53263f9c07a1...v1.0.0) (2025-08-16)

### Features

* Add deauthorize route ([63d707](https://github.com/groton-school/slim-oauth2-api-proxy/commit/63d707e19b1d0d72bd629fc005a0188a208725e8))
* Add owner endpoint ([19812c](https://github.com/groton-school/slim-oauth2-api-proxy/commit/19812ca190ad1607343bde71a6541a5cd8842d7f))
* Add refresh token route ([02fee6](https://github.com/groton-school/slim-oauth2-api-proxy/commit/02fee6f516799b105cbca5f883599e0df891778e))
* Automatically refresh stored token ([7facdd](https://github.com/groton-school/slim-oauth2-api-proxy/commit/7facdd61d4589717cebf71d31ed01d06665ea442))
* Default settings ([14be9a](https://github.com/groton-school/slim-oauth2-api-proxy/commit/14be9a38ccf576c0817496d468ad042cb96e3a4d))
* Proxy API requests ([63b20c](https://github.com/groton-school/slim-oauth2-api-proxy/commit/63b20c228a0dc84f3c98590eaeceedd645f966c1))
* Use factory model for providers ([0c6ca4](https://github.com/groton-school/slim-oauth2-api-proxy/commit/0c6ca4c171b42f5f38292a3fff04c49de1a411d7))
* User Provider\Defaults to implement most ProviderInterface methods ([05271d](https://github.com/groton-school/slim-oauth2-api-proxy/commit/05271da474954bd00e249f24cfe5c0a191a7630f))

### Bug Fixes

* Catch GuzzleException on request ([687541](https://github.com/groton-school/slim-oauth2-api-proxy/commit/687541ff284213479d11ecc1a51468fd58d7dd90))
* Check for stored token before instantiating ([d9232d](https://github.com/groton-school/slim-oauth2-api-proxy/commit/d9232dd861959415e98ad57aa327c8a6ed5fdb2b))
* De-over-define LeagueProviderInterface ([cb3074](https://github.com/groton-school/slim-oauth2-api-proxy/commit/cb3074e332ee575ae6d0661ee727a855478929ec))
* Expire deauthorized token cookie ([99177f](https://github.com/groton-school/slim-oauth2-api-proxy/commit/99177f315a64e8cfc53859e45fb669e269938247))
* Fix regex ([e81df9](https://github.com/groton-school/slim-oauth2-api-proxy/commit/e81df994921d1a06cfc0b3245d296330e1433e61))
* No need for redirect on refresh ([e66946](https://github.com/groton-school/slim-oauth2-api-proxy/commit/e6694631ca33c1121b7208636ed44fc14585cbf0))
* Remove extraneous timestamp ([c78039](https://github.com/groton-school/slim-oauth2-api-proxy/commit/c78039cc1c07fac5544943410bd82030c6a4c351))


---

