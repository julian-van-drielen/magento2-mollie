<?php
/**
 * Copyright Magmodules.eu. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Mollie\Payment\GraphQL\Resolver\Checkout;

use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Mollie\Payment\Service\Order\StartTransaction;

class CreateMollieTransaction
{
    /**
     * @var StartTransaction
     */
    private $startTransaction;

    /**
     * @var string|null
     */
    protected static $issuer = null;

    public function __construct(
        StartTransaction $startTransaction
    ) {
        $this->startTransaction = $startTransaction;
    }

    /**
     * @inheritDoc
     */
    public function resolve($field, $context, $info, array $value = null, array $args = null)
    {
        self::$issuer = isset($args['input']['issuer']) ? $args['input']['issuer'] : null;
        $paymentToken = $args['input']['payment_token'];

        if (!$paymentToken) {
            throw new GraphQlInputException(__('The field "payment_token" is required for this request'));
        }

        return ['checkout_url' => $this->startTransaction->byPaymentToken($paymentToken)];
    }

    /**
     * @return string|null
     */
    public static function getIssuer()
    {
        return self::$issuer;
    }
}