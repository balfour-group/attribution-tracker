# attribution-tracker

A library for assigning an attribution channel to an entity based on tracking properties of that entity.

*This library is in early release and is pending unit tests.*

## Use Case

A typical use case is that you have a trackable entity (a `user` or `lead`) which registers
or is created via an API, with one or many tracking properties (`http_referer`, `utm_source`, `utm_medium`
`gclid`, etc).

Given the entity and its tracking properties, you'd like to classify it into an attribution
channel, and store that attribution channel (`Organic Search -> Google`) against the entity for reporting purposes.

## Installation

```bash
composer require balfour/attribution-tracker
```

## Usage

**Step 1:**

You create an AttributionTracker and define your tracking rules.

This repo includes a `GenericChannel` class for ease of use; although you can create a custom class
implementing the `ChannelInterface`.  In a real world app, your channels & rules might be models loaded
from a database.

```php
use Balfour\AttributionTracker\AttributionTracker;
use Balfour\AttributionTracker\GenericChannel;
use Balfour\AttributionTracker\Rules\GoogleAdsRule;
use Balfour\AttributionTracker\Rules\GoogleTrackingRule;
use Balfour\AttributionTracker\Rules\HttpRefererRule;

$tracker = new AttributionTracker();

$channel1 = new GenericChannel('Direct', 'Direct');
$tracker->setDefaultChannel($channel1);
// if the tracker can't find a matching rule, the default channel is returned

$channel2 = new GenericChannel('Paid Media', 'Google');
$rule1 = new GoogleAdsRule($channel2);
$tracker->addRule($rule1);

// this will match on utm_source = google AND utm_medium = ppc
$rule2 = new GoogleTrackingRule($channel2, 'google', 'ppc');
$tracker->addRule($rule2);

$channel3 = new GenericChannel('Referral', 'Facebook');
$rule3 = new HttpRefererRule($channel3, 'lm.facebook.com');
$rule4 = new HttpRefererRule($channel3, 'm.facebook.com');
$rule5 = new HttpRefererRule($channel3, 'l.facebook.com');
$rule6 = new HttpRefererRule($channel3, 'www.facebook.com');
$rule7 = new HttpRefererRule($channel3, 'facebook.com');
$tracker->addRules([$rule3, $rule4, $rule5, $rule6, $rule7]);
```

**Step 2:**

You have an object, implementing the `TrackableEntityInterface`, which you'd like to classify.

A `GenericTrackableEntity` is included in this repo; however in a real app, this may be a `user`
or `lead` model.

```php
use Balfour\AttributionTracker\GenericTrackableEntity;
use Balfour\AttributionTracker\TrackingMeta;

$props = new \stdClass();
$props->http_referer = 'https://google.co.za';
$props->utm_source = 'google';
$props->utm_medium = 'ppc';
$meta = new TrackingMeta($props);

$entity = new GenericTrackableEntity($meta);
```

**Step 3:**

With your `AttributionTracker` and `TrackableEntityInterface`, the entity can be classified.

```php
$channel = $tracker->getChannel($entity);

var_dump($channel);
var_dump($channel->getCategory()); // Paid Media
var_dump($channel->getLabel()); // Google
var_dump($channel->getFullName()); // Paid Media -> Google
```
