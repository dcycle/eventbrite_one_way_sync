<?php

// @codingStandardsIgnoreStart
namespace Drupal\webhook_receiver {
  class WebhookReceiver {
    public function webhooks() {
      return [];
    }
  }
  class WebhookReceiverPluginBase {
    public function x() {

    }
  }
}

namespace Drupal\webhook_receiver\WebhookReceiverActivityLog {
  interface WebhookReceiverActivityLogInterface {
    public function logThrowable($t);
  }
}
// @codingStandardsIgnoreEnd
