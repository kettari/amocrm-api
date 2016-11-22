<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 12.07.2016
 * Time: 15:03
 */

namespace AmoCrm\Api\Response;


use AmoCrm\Api\Exception\AmoInvalidDataTypeException;

/**
 * Class AmoResponseCode
 *
 * @package AmoCrm\Api
 */
class AmoResponseCode {

  /**
   * AmoCRM error codes and descriptions
   */
  const AMO_ERROR_CODES = [
    // Authorization errors
    110  => 'Неправильный логин или пароль',
    111  => 'Неправильный код капчи',
    112  => 'Пользователь не состоит в данном аккаунте',
    113  => 'Доступ к данному аккаунту запрещён с Вашего IP адреса',
    101  => 'Аккаунт не найден',
    401  => 'На сервере нет данных аккаунта',

    // Account errors
    102  => 'POST-параметры должны передаваться в формате JSON',
    103  => 'Параметры не переданы',
    104  => 'Запрашиваемый метод API не найден',

    // Contact errors
    201  => 'Добавление контактов: пустой массив',
    202  => 'Добавление контактов: нет прав',
    203  => 'Добавление контактов: системная ошибка при работе с дополнительными полями',
    204  => 'Добавление контактов: дополнительное поле не найдено',
    205  => 'Добавление контактов: контакт не создан',
    206  => 'Добавление/Обновление контактов: пустой запрос',
    207  => 'Добавление/Обновление контактов: неверный запрашиваемый метод',
    208  => 'Обновление контактов: пустой массив',
    209  => 'Обновление контактов: требуются параметры "id" и "last_modified"',
    210  => 'Обновление контактов: системная ошибка при работе с дополнительными полями',
    211  => 'Обновление контактов: дополнительное поле не найдено',
    212  => 'Обновление контактов: контакт не обновлён',
    219  => 'Список контактов: ошибка поиска, повторите запрос позднее',

    // Leads error
    213  => 'Добавление сделок: пустой массив',
    214  => 'Добавление/Обновление сделок: пустой запрос',
    215  => 'Добавление/Обновление сделок: неверный запрашиваемый метод',
    216  => 'Обновление сделок: пустой массив',
    217  => 'Обновление сделок: требуются параметры "id", "last_modified", "status_id", "name"',
    240  => 'Добавление/Обновление сделок: неверный параметр "id" дополнительного поля',

    // Event errors
    218  => 'Добавление событий: пустой массив',
    221  => 'Список событий: требуется тип',
    222  => 'Добавление/Обновление событий: пустой запрос',
    223  => 'Добавление/Обновление событий: неверный запрашиваемый метод (GET вместо POST)',
    224  => 'Обновление событий: пустой массив',
    225  => 'Обновление событий: события не найдены',

    // Task errors
    227  => 'Добавление задач: пустой массив',
    228  => 'Добавление/Обновление задач: пустой запрос',
    229  => 'Добавление/Обновление задач: неверный запрашиваемый метод',
    230  => 'Обновление задач: пустой массив',
    231  => 'Обновление задач: задачи не найдены',
    232  => 'Добавление событий: ID элемента или тип элемента пустые либо неккоректные',
    233  => 'Добавление событий: по данному ID элемента не найдены некоторые контакты',
    234  => 'Добавление событий: по данному ID элемента не найдены некоторые сделки',
    235  => 'Добавление задач: не указан тип элемента',
    236  => 'Добавление задач: по данному ID элемента не найдены некоторые контакты',
    237  => 'Добавление задач: по данному ID элемента не найдены некоторые сделки',
    238  => 'Добавление контактов: отсутствует значение для дополнительного поля',
    244  => 'Добавление сделок: нет прав',

    // Other errors
    400  => 'Неверная структура массива передаваемых данных, либо не верные идентификаторы кастомных полей',
    403  => 'Аккаунт заблокирован, за неоднократное превышение количества запросов в секунду',
    429  => 'Превышено допустимое количество запросов в секунду',
    2002 => 'По вашему запросу ничего не найдено',
  ];

  /**
   * amoCRM extended error code
   *
   * @var integer
   */
  protected $error_code;

  /**
   * amoCRM extended error description
   *
   * @var string
   */
  protected $error_description;

  /**
   * AmoResponseCode constructor.
   *
   * @param $code
   * @param $description
   */
  public function __construct($code, $description) {
    $this->error_code = $code;
    $this->error_description = $description;
  }

  /**
   * Decode response and return error message with code.
   *
   * @param array $response
   * @return \AmoCrm\Api\Response\AmoResponseCode
   * @throws AmoInvalidDataTypeException
   */
  public static function describe($response) {
    if (!is_array($response)) {
      throw new AmoInvalidDataTypeException('Array expected as a response parameter');
    }

    if (isset($response['response'])) {

      // Try to guess where the error code is
      if (isset($response['response']['unsorted'])
        && isset($response['response']['unsorted']['add'])
        && isset($response['response']['unsorted']['add']['error_code'])) {
        $error_code = $response['response']['unsorted']['add']['error_code'];
        $error_description = $response['response']['unsorted']['add']['error'];
      }
      else {
        throw new AmoInvalidDataTypeException('Invalid response object from the AmoCRM');
      }

      $codes = static::AMO_ERROR_CODES;
      $result_description = isset($codes[$error_code])
        ? sprintf('%s (%s)', $codes[$error_code], $error_description)
        : sprintf('Undescribed error (%s)', $error_description);
      $result_code = $error_code;

      return new AmoResponseCode($result_code, $result_description);
    }
    else {
      throw new AmoInvalidDataTypeException('Invalid response object from the AmoCRM');
    }
  }

  /**
   * @return int
   */
  public function getErrorCode() {
    return $this->error_code;
  }

  /**
   * @return string
   */
  public function getErrorDescription() {
    return $this->error_description;
  }

}