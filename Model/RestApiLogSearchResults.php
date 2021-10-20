<?php
/**
 * Fast_Checkout
 *
 * PHP version 7.3
 *
 * @package   Fast_Checkout
 * @author    Borngroup <support@borngroup.com>
 * @copyright 2020 Copyright BORN Commerce Pvt Ltd, https://www.borngroup.com/
 * @license   https://www.borngroup.com/ Borngroup
 * @link      https://www.fast.co/
 */

namespace Fast\Checkout\Model;

use Fast\Checkout\Api\Data\RestApiLogSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

/**
 * class for search result
 * Class RestApiLogSearchResults
 */
class RestApiLogSearchResults extends SearchResults implements RestApiLogSearchResultsInterface
{

}
