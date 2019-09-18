<?php

namespace Quantum\base\Services;

use Illuminate\Contracts\Events\Dispatcher;
use Settings;
use Symfony\Component\HttpFoundation\Session\Session;
use Exception;
use InvalidArgumentException;

class CartService
{
    const CARTSUFFIX = '_cart';

    protected $session;

    protected $event;

    /**
     * Manage cart items
     *
     * @var CartCollection
     */
    protected $collection;
    /**
     * Cart name
     *
     * @var string
     */
    protected $name = "Qcommerce";

    /**
     * The Eloquent model a cart is associated with
     *
     * @var string
     */
    protected $associatedModel;
    /**
     * An optional namespace for the associated model
     *
     * @var string
     */
    protected $associatedModelNamespace;
    
    protected $allowedModels;

    public function __construct($session, Dispatcher $event, $name = null)
    {
        $this->session = new Session();

        $this->event = $event;
        $this->collection = new CartCollection();
        if ($name) {
            $this->setCart($name);
        }
        $this->setAllowedModels();
    }
    
    private function setAllowedModels()
    {
        $this->allowedModels['membership'] = '\Quantum\base\Models\MembershipTypes';
    }

    public function addUrl($model, $modelId, $members = true)
    {
        $members = $members ? 'members' : '';
        return $members.'/cart/add/'.$model.'/'.$modelId;
    }
    
    public function addFromModel($model, $modelId)
    {
        if(!isset($this->allowedModels[$model])) return false;
       // $item = $this->allowedModels[$model]::where('slug', $modelId)->where('allow_user_signups', '1')->where('status', 'active')->firstOrFail();
        //TODO reinstate dynamic call above
        $item = \Quantum\base\Models\MembershipTypes::where('slug', $modelId)->where('allow_user_signups', '1')->where('status', 'active')->tenant()->firstOrFail();

        if($model == 'membership') {
            \Cart::clear();
            if($this->hasMembership($item->id))
            {
                \Flash::error('The selected membership is already active on your account.');
                return back();
                //return redirect(url(\Settings::get('members_home_page')));
            }
        }

        $added = $this->add([
            'id'       => ucfirst($model).'-'.$item->title,
            'name'     => $item->title,
            'summary'    => $item->summary,
            'quantity' => 1,
            'price'    => $item->amount,
            'type'    => $model,
            'expires'    => isset($item->expires) ? $item->expires : false,
            'subscription' => isset($item->subscription) ? $item->subscription : false,
            'model_id'    => $item->id,
            'model' => $this->allowedModels[$model]
        ]);
        return $added;
    }
    
    public function hasMembership($membership_id)
    {
        if(!\Auth::check()) return false;
        
        if($memberships = \Auth::user()->membership){
            foreach ($memberships as $membership)
            {
                if($membership->membership_types_id == $membership_id && $membership->status == 'active') return true;
            }
        }
        return false;
    }

    public function showCheckout()
    {
        if(\Auth::check())
        {
           return \Redirect::to(Settings::get('members_checkout_page'));
        }
        return redirect(url('/checkout'));
    }

    public function setCart($name)
    {
        if (empty($name)) {
            throw new InvalidArgumentException('Cart name can not be empty.');
        }
        $this->name = $name . self::CARTSUFFIX;
    }
    public function getCart()
    {
        return $this->name;
    }

    /**
     * Set the current cart name
     *
     * @param $name
     * @return $this
     * @internal param string $instance Cart instance name
     */
    public function named($name)
    {
        $this->setCart($name);
        return $this;
    }

    /**
     * Add an item to the cart.
     *
     * @param array|Array $product
     */
    public function add(Array $product, $type = 'membership')
    {
        $this->collection->validateItem($product);
        // If item already added, increment the quantity
        if ($this->has($product['id'])) {
            $item = $this->get($product['id']);
            return $this->updateQty($item->id, $item->quantity + $product['quantity']);
        }
        $this->collection->setItems($this->session->get($this->getCart(), []));
        $items = $this->collection->insert($product);
        //if subscription, clear the cart
        if($type == 'membership') $this->clear();
        if((isset($product['clear_cart'])) && ($product['clear_cart'] == true)) $this->clear();
        //add to cart
        $this->session->set($this->getCart(), $items);
        //if subscription goto checkout
        if($type == 'membership') return $this->showCheckout();
        if((isset($product['show_checkout'])) && ($product['show_checkout'] == true)) return $this->showCheckout();

        return $this->collection->make($items);
    }
    /**
     * Update an item.
     *
     * @param  Array  $product
     */
    public function update(Array $product)
    {
        $this->collection->setItems($this->session->get($this->getCart(), []));
        if (! isset($product['id'])) {
            throw new Exception('id is required');
        }
        if (! $this->has($product['id'])) {
            throw new Exception('There is no item in shopping cart with id: ' . $product['id']);
        }
        $item = array_merge((array) $this->get($product['id']), $product);
        $items = $this->collection->insert($item);
        $this->session->set($this->getCart(), $items);
        return $this->collection->make($items);
    }
    /**
     * Update quantity of an Item.
     *
     * @param mixed $id
     * @param int $quantity
     *
     */
    public function updateQty($id, $quantity)
    {
        $item = (array) $this->get($id);
        $item['quantity'] = $quantity;
        return $this->update($item);
    }
    /**
     * Update price of an Item.
     *
     * @param mixed $id
     * @param float $price
     *
     */
    public function updatePrice($id, $price)
    {
        $item = (array) $this->get($id);
        $item['price'] = $price;
        return $this->update($item);
    }
    /**
     * Remove an item from the cart.
     *
     * @param  mixed $id
     */
    public function remove($id)
    {
        $items = $this->session->get($this->getCart(), []);
        unset($items[$id]);
        $this->session->set($this->getCart(), $items);
        return $this->collection->make($items);
    }
    /**
     * Helper wrapper for cart items.
     *
     */
    public function items()
    {
        return $this->getItems();
    }
    /**
     * Get all the items.
     *
     */
    public function getItems()
    {
        return $this->collection->make($this->session->get($this->getCart()));
    }
    /**
     * Get a single item.
     * @param  $id
     *
     * @return Array
     */
    public function get($id)
    {
        $this->collection->setItems($this->session->get($this->getCart(), []));
        return $this->collection->findItem($id);
    }
    /**
     * Check an item exist or not.
     * @param  $id
     *
     * @return boolean
     */
    public function has($id)
    {
        $this->collection->setItems($this->session->get($this->getCart(), []));
        return $this->collection->findItem($id)? true : false;
    }
    /**
     * Get the number of Unique items in the cart
     *
     * @return int
     */
    public function count()
    {
        $items = $this->getItems();
        return $items->count();
    }
    /**
     * Get the total amount
     *
     * @return float
     */
    public function getTotal()
    {
        $items = $this->getItems();
        return $items->sum(function($item) {
            return $item->price * $item->quantity;
        });
    }
    /**
     * Get the total quantities of items in the cart
     *
     * @return int
     */
    public function totalQuantity()
    {
        $items = $this->getItems();
        return $items->sum(function($item) {
            return $item->quantity;
        });
    }
    /**
     * Clone a cart to another
     *
     * @param  mix $cart
     *
     * @return void
     */
    public function copy($cart)
    {
        if (is_object($cart)) {
            if (! $cart instanceof \Quantum\base\Services\CartService) {
                throw new InvalidArgumentException("Argument must be an instance of " . get_class($this));
            }
            $items = $this->session->get($cart->getCart(), []);
        } else {
            if (! $this->session->has($cart . self::CARTSUFFIX)) {
                throw new Exception('Cart does not exist: ' . $cart);
            }
            $items = $this->session->get($cart . self::CARTSUFFIX, []);
        }
        $this->session->set($this->getCart(), $items);
    }
    /**
     * Alias of clear (Deprecated)
     *
     * @return void
     */
    public function flash()
    {
        $this->clear();
    }
    /**
     * Empty cart
     *
     * @return void
     */
    public function clear()
    {
        $this->session->remove($this->getCart());
    }


}