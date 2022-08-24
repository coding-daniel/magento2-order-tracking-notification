<?php declare(strict_types=1);

namespace CodingDaniel\OrderTrackingNotification\Plugin\Model\Order\Shipment;

use Magento\Sales\Api\Data\ShipmentTrackInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\ShipmentRepositoryInterface;
use Magento\Sales\Model\Order\Shipment\Notifier;
use Magento\Sales\Model\Order\Shipment\TrackRepository;

/**
 * Plugin to notify customer via email after tracking information has been added to existing shipment
 */
class NotifyCustomerAfterOrderTracking
{
    /**
     * @var Notifier
     */
    protected Notifier $notifier;

    /**
     * @var ShipmentRepositoryInterface
     */
    protected ShipmentRepositoryInterface $shipment;

    /**
     * @var OrderRepositoryInterface
     */
    protected OrderRepositoryInterface $order;

    /**
     * @param Notifier $notifier
     * @param ShipmentRepositoryInterface $shipmentRepositoryInterface
     * @param OrderRepositoryInterface $orderRepositoryInterface
     */
    public function __construct(
        Notifier $notifier,
        ShipmentRepositoryInterface $shipmentRepositoryInterface,
        OrderRepositoryInterface $orderRepositoryInterface
    ) {
        $this->notifier = $notifier;
        $this->shipment = $shipmentRepositoryInterface;
        $this->order = $orderRepositoryInterface;
    }

    /**
     * Send out email when tracking info is added to existing shipment
     *
     * @param TrackRepository $subject
     * @param callable $proceed
     * @param ShipmentTrackInterface $entity
     * @return ShipmentTrackInterface
     */
    public function aroundSave(
        TrackRepository $subject,
        callable $proceed,
        ShipmentTrackInterface $entity
    ): ShipmentTrackInterface {

        // beforeSave gets the value of extension_attributes -> notify
        $shipmentTrackingNotify = $entity->getExtensionAttributes()->getNotify();

        // Continue calls to next methods in chain
        $returnValue = $proceed($entity);

        // afterSave to send shipment tracking notification
        if ($shipmentTrackingNotify) {
            $orderId = $returnValue->getOrderId();
            $shipmentId = $returnValue->getParentId();

            $shipment = $this->shipment->get($shipmentId);
            $order = $this->order->get($orderId);
            $this->notifier->notify($order, $shipment);
        }

        return $returnValue;
    }
}
