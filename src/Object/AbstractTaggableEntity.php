<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 07.03.2017
 * Time: 20:18
 */

namespace AmoCrm\Client\Object;


abstract class AbstractTaggableEntity extends AbstractTimeAwareEntity {

  /**
   * Tags
   *
   * @var array
   */
  protected $tags = [];

  /**
   * Contact constructor.
   *
   * @param array $data
   */
  public function __construct(array $data) {
    parent::__construct($data);

    if (isset($data['tags'])) {
      if (is_array($data['tags'])) {

        // Unset possible tags set by parent
        $this->tags = [];

        foreach ($data['tags'] as $tag_item) {
          if (isset($tag_item['id'])) {
            $this->tags[$tag_item['id']] = $tag_item['name'];
          }
        }
      } else {
        $this->tags = $data['tags'];
      }
    }
  }

  /**
   * Return array ready to be sent to AmoCRM
   *
   * @return array
   */
  public function toArray() {
    // Build result array
    $result = parent::toArray();
    if (count($this->tags) > 0) {
      $result['tags'] = implode(',', $this->tags);
    }

    return $result;
  }

  /**
   * @return array
   */
  public function getTags() {
    return $this->tags;
  }

  /**
   * @param array $tags
   * @return AbstractTaggableEntity
   */
  public function setTags($tags) {
    $this->tags = $tags;

    return $this;
  }

}