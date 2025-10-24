<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $service
 * @property string $tracking_id
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\AnalyticFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Analytic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Analytic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Analytic query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Analytic whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Analytic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Analytic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Analytic whereService($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Analytic whereTrackingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Analytic whereUpdatedAt($value)
 */
	class Analytic extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AttributeValue> $values
 * @property-read int|null $values_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribute query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribute whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribute whereUpdatedAt($value)
 */
	class Attribute extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $attribute_id
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Attribute $attribute
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttributeValue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttributeValue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttributeValue query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttributeValue whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttributeValue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttributeValue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttributeValue whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AttributeValue whereValue($value)
 */
	class AttributeValue extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property string|null $session_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CartItem> $items
 * @property-read int|null $items_count
 * @method static \Database\Factories\CartFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereUserId($value)
 */
	class Cart extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $cart_id
 * @property int $variant_id
 * @property int $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cart $cart
 * @method static \Database\Factories\CartItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereCartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereVariantId($value)
 */
	class CartItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Database\Factories\CategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 */
	class Category extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $main_image
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\CollectionProduct|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Database\Factories\CollectionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection whereMainImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection whereUpdatedAt($value)
 */
	class Collection extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $collection_id
 * @property int $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Collection $collection
 * @property-read \App\Models\Product $product
 * @method static \Database\Factories\CollectionProductFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CollectionProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CollectionProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CollectionProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CollectionProduct whereCollectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CollectionProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CollectionProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CollectionProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CollectionProduct whereUpdatedAt($value)
 */
	class CollectionProduct extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $subject
 * @property string $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereUpdatedAt($value)
 */
	class Contact extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $phone_code
 * @property string $currency_code
 * @property string|null $currency_sympol
 * @property string $tax_rate
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Customer> $customers
 * @property-read int|null $customers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Newsletter> $newsletters
 * @property-read int|null $newsletters_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\State> $states
 * @property-read int|null $states_count
 * @method static \Database\Factories\CountryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCurrencyCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCurrencySympol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country wherePhoneCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereTaxRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereUpdatedAt($value)
 */
	class Country extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $code
 * @property \App\Enums\CouponType $type
 * @property int $value
 * @property int|null $min_order_amount
 * @property int|null $usage_limit
 * @property int $used_count
 * @property \Illuminate\Support\Carbon $starts_at
 * @property \Illuminate\Support\Carbon $expires_at
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @method static \Database\Factories\CouponFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereMinOrderAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereUsageLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereUsedCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereValue($value)
 */
	class Coupon extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property int|null $country_id
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string|null $phone_number
 * @property int|null $billing_country_id
 * @property string|null $billing_state
 * @property string|null $billing_city
 * @property string|null $billing_address
 * @property string|null $billing_building_number
 * @property int|null $shipping_country_id
 * @property string|null $shipping_state
 * @property string|null $shipping_city
 * @property string|null $shipping_address
 * @property string|null $shipping_building_number
 * @property int $use_billing_for_shipping
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\Country|null $billingCountry
 * @property-read \App\Models\Country|null $country
 * @property-read mixed $full_name
 * @property-read mixed $loyalty_points
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\Country|null $shippingCountry
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\CustomerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereBillingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereBillingBuildingNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereBillingCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereBillingCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereBillingState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereShippingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereShippingBuildingNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereShippingCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereShippingCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereShippingState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereUseBillingForShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereUserId($value)
 */
	class Customer extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $points
 * @property string $action
 * @property string|null $description
 * @property string|null $source_type
 * @property int|null $source_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $source
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoyaltyTransaction confirmed()
 * @method static \Database\Factories\LoyaltyTransactionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoyaltyTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoyaltyTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoyaltyTransaction pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoyaltyTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoyaltyTransaction whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoyaltyTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoyaltyTransaction whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoyaltyTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoyaltyTransaction wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoyaltyTransaction whereSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoyaltyTransaction whereSourceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoyaltyTransaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoyaltyTransaction whereUserId($value)
 */
	class LoyaltyTransaction extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $email
 * @property string $token
 * @property bool $verified
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\NewsletterFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter verified()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter whereVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter withoutTrashed()
 */
	class Newsletter extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $order_number
 * @property int|null $user_id
 * @property int|null $customer_id
 * @property string|null $guest_token
 * @property string|null $first_name
 * @property string|null $last_name
 * @property int|null $country_id
 * @property \App\Models\State|null $state
 * @property string|null $city
 * @property string|null $email
 * @property string|null $phone_number
 * @property int $subtotal
 * @property int $tax_amount
 * @property int $shipping_amount
 * @property int $discount_amount
 * @property int $total_amount
 * @property string $currency
 * @property string $billing_address
 * @property string|null $billing_building_number
 * @property string|null $shipping_address
 * @property string|null $shipping_building_number
 * @property bool $use_billing_for_shipping
 * @property string|null $notes
 * @property int|null $coupon_id
 * @property int $is_guest
 * @property string $payment_method
 * @property \App\Enums\PaymentStatus $payment_status
 * @property \App\Enums\OrderStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Country|null $country
 * @property-read \App\Models\Coupon|null $coupon
 * @property-read \App\Models\Customer|null $customer
 * @property-read mixed $currency_symbol
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $items
 * @property-read int|null $items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \App\Models\Shipping|null $shipping
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\OrderFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereBillingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereBillingBuildingNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCouponId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereGuestToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereIsGuest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereShippingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereShippingAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereShippingBuildingNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUseBillingForShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order withoutTrashed()
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property int|null $variant_id
 * @property int $quantity
 * @property int $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\Variant|null $variant
 * @method static \Database\Factories\OrderItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereVariantId($value)
 */
	class OrderItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\PageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereUpdatedAt($value)
 */
	class Page extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $order_id
 * @property string $provider
 * @property string|null $provider_reference
 * @property string $status
 * @property string $currency
 * @property int $amount_minor
 * @property string|null $return_url
 * @property string|null $cancel_url
 * @property string|null $webhook_signature
 * @property array<array-key, mixed>|null $meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Order $order
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PaymentTransaction> $transactions
 * @property-read int|null $transactions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereAmountMinor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCancelUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereProviderReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereReturnUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereWebhookSignature($value)
 */
	class Payment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $payment_id
 * @property string $event
 * @property string|null $provider_status
 * @property array<array-key, mixed>|null $payload
 * @property string|null $idempotency_key
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Payment $payment
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentTransaction whereEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentTransaction whereIdempotencyKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentTransaction wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentTransaction wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentTransaction whereProviderStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentTransaction whereUpdatedAt($value)
 */
	class PaymentTransaction extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $category_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property bool $featured
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\CollectionProduct|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Collection> $collections
 * @property-read int|null $collections_count
 * @property-read mixed $compare_price
 * @property-read mixed $discount_percentage
 * @property-read mixed $has_variants
 * @property-read mixed $price
 * @property-read mixed $quantity
 * @property-read int|null $variants_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Variant> $variants
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Variant> $variantsInStock
 * @property-read int|null $variants_in_stock_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Variant> $variantsOptimized
 * @property-read int|null $variants_optimized_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Wishlist> $wishlists
 * @property-read int|null $wishlists_count
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 */
	class Product extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\SettingFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereValue($value)
 */
	class Setting extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $state_id
 * @property int $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\State|null $state
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereUpdatedAt($value)
 */
	class Shipping extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $country_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Country|null $country
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shipping> $shippings
 * @property-read int|null $shippings_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|State newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|State newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|State query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|State whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|State whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|State whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|State whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|State whereUpdatedAt($value)
 */
	class State extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $is_admin
 * @property-read \App\Models\Customer|null $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoyaltyTransaction> $loyaltyTransactions
 * @property-read int|null $loyalty_transactions_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Wishlist> $wishlist
 * @property-read int|null $wishlist_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent implements \Filament\Models\Contracts\FilamentUser {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $product_id
 * @property string $sku
 * @property string $wick_type
 * @property string $size
 * @property int $stock
 * @property numeric|null $price
 * @property numeric|null $compare_price
 * @property numeric|null $weight
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read mixed $formatted_price
 * @property-read mixed $is_in_stock
 * @property-read mixed $stock_status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VariantOption> $options
 * @property-read int|null $options_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant bySize($size)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant inStock()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereComparePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereWickType($value)
 */
	class Variant extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $variant_id
 * @property int $attribute_value_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AttributeValue $attributeValue
 * @property-read \App\Models\Variant $variant
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariantOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariantOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariantOption query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariantOption whereAttributeValueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariantOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariantOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariantOption whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariantOption whereVariantId($value)
 */
	class VariantOption extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\WishlistFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist whereUserId($value)
 */
	class Wishlist extends \Eloquent {}
}

