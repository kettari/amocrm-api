<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 29.07.2016
 * Time: 16:32
 */

namespace AmoCrm\Api\Response;


class AmoResponseFactory {

  /**
   * AmoResponseFactory builder.
   *
   * @param string $link
   * @param integer $http_code
   * @param mixed $raw_result
   * @return \AmoCrm\Api\Response\AmoBaseResponse
   */
  public static function build($link, $http_code, $raw_result) {

    // Check API methods one by one and create appropriate response object
    if (preg_match('/.*\/api\/unsorted\/add.*/i', $link)) {
      return new AmoUnsortedAddResponse($http_code, $raw_result);
    }
    elseif (preg_match('/.*\/private\/api\/v2\/json\/([a-z]+)\/list.*/i', $link, $matches)) {
      switch ($matches[1]) {
        case 'leads':
          return new AmoLeadsListResponse($http_code, $raw_result);
          break;
        case 'contacts':
          return new AmoContactsListResponse($http_code, $raw_result);
          break;
        case 'tasks':
          return new AmoTasksListResponse($http_code, $raw_result);
          break;
      }
    }
    elseif (preg_match('/.*\/private\/api\/v2\/json\/([a-z]+)\/set.*/i', $link, $matches)) {
      switch ($matches[1]) {
        case 'leads':
          return new AmoLeadsSetResponse($http_code, $raw_result);
          break;
        case 'contacts':
          return new AmoContactsSetResponse($http_code, $raw_result);
          break;
        case 'tasks':
          return new AmoTasksSetResponse($http_code, $raw_result);
          break;
        case 'notes':
          return new AmoNotesSetResponse($http_code, $raw_result);
          break;
      }
    }
    elseif (preg_match('/.*\/private\/api\/v2\/json\/([a-z]+)\/links.*/i', $link, $matches)) {
      switch ($matches[1]) {
        case 'contacts':
          return new AmoContactsLinksResponse($http_code, $raw_result);
          break;
      }
    }

    return new AmoBaseResponse($http_code, $raw_result);
  }
}