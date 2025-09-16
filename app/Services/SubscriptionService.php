<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionService
{
    protected $subscribe;

    public function __construct(Subscription $subscribe)
    {
        $this->subscribe = $subscribe;
    }

    public function create($creatorId)
    {
        if (User::posses($creatorId) &&
            Auth::id() !== (int) $creatorId
        ) {

            $subscriptionBuilder = Subscription::where('subscriber_id', Auth::id())
                                               ->where('creator_id', $creatorId);

            if ($subscriptionBuilder->exists()) {
                $subscriptionBuilder->delete();

                return 'Подписка отменена';
            }

            $this->subscribe::create([
                'subscriber_id' => Auth::id(),
                'creator_id' => (int) $creatorId
            ]);

            return 'Вы подписались';
        }
        return false;
    }
}
