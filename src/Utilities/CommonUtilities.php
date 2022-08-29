<?php

namespace Drupal\eventbrite_one_way_sync\Utilities;

/**
 * Common utilities which can be used throughout the module.
 */
trait CommonUtilities {

  /**
   * Throw an exception if a string is not empty.
   *
   * @param string $string
   *   A string which cannot be empty.
   * @param string $error_message
   *   An error message.
   */
  public function assertNonEmptyString(string $string, string $error_message) {
    if (!$string) {
      throw new \Exception($error_message);
    }
  }

  /**
   * Reprensent a \Throwable as a string.
   *
   * @param \Throwable $t
   *   A throwable.
   *
   * @return string
   *   A string representation of the \Throwable.
   */
  public function throwableToString(\Throwable $t) : string {
    return $t->getMessage() . ' (' . $t->getFile() . ':' . $t->getLine() . ')';
  }

  /**
   * Throw an exception if an array is not empty.
   *
   * @param mixed $candidate
   *   A string which cannot be empty.
   * @param string $error_message
   *   An error message.
   */
  public function assertNonEmptyArray($candidate, string $error_message) {
    if (!is_array($candidate) || !count($candidate)) {
      throw new \Exception($error_message);
    }
  }

}
