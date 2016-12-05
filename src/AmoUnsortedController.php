<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 26.07.2016
 * Time: 16:17
 */

namespace AmoCrm\Api;


use AmoCrm\Api\Object\AmoUnsortedObject;

class AmoUnsortedController extends AmoBaseController {

  /**
   * Create unsorted item in the AmoCRM
   *
   * @param \AmoCrm\Api\Object\AmoUnsortedObject $unsorted
   * @return bool
   */
  public function create(AmoUnsortedObject $unsorted) {

    // Prepare link
    $link = $this->getBaseLink() . '/api/unsorted/add';
    // Add auth
    $link = $link . sprintf('?login=%s&api_key=%s',
        $this->user_login, $this->api_hash);

    // Prepare data for request
    $data['request']['unsorted'] = [
      'category' => 'forms',
      'add'      => [
        $unsorted->getArray(),
      ],
    ];

    // Send request to AmoCRM API
    $amo_response = $this->sendRequest('POST', $link, $data);

    return !$amo_response->isErrorFlag();
  }

}