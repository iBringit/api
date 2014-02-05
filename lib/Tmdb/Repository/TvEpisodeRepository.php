<?php
/**
 * This file is part of the Tmdb PHP API created by Michael Roterman.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package Tmdb
 * @author Michael Roterman <michael@wtfz.net>
 * @copyright (c) 2013, Michael Roterman
 * @version 0.0.1
 */
namespace Tmdb\Repository;

use Tmdb\Exception\RuntimeException;
use Tmdb\Factory\TvEpisodeFactory;
use Tmdb\Model\Tv\Episode\QueryParameter\AppendToResponse;
use Tmdb\Model\Tv;
use Tmdb\Model\Tv\Season;
use Tmdb\Model\Tv\Episode;

class TvEpisodeRepository extends AbstractRepository {

    /**
     * Load a tv season with the given identifier
     *
     * If you want to optimize the result set/bandwidth you should define the AppendToResponse parameter
     *
     * @param $tvShow Tv|integer
     * @param $season Season|integer
     * @param $episode Episode|integer
     * @param $parameters
     * @param $headers
     * @throws RuntimeException
     * @return null|\Tmdb\Model\AbstractModel
     */
    public function load($tvShow, $season, $episode, array $parameters = array(), array $headers = array())
    {
        if ($tvShow instanceof Tv) {
            $tvShow = $tvShow->getId();
        }

        if ($season instanceof Season) {
            $season = $season->getId();
        }

        if ($episode instanceof Tv\Episode) {
            $episode = $episode->getId();
        }

        if (null == $tvShow || null == $season || null == $episode) {
            throw new RuntimeException('Not all required parameters to load an tv episode are present.');
        }

        if (empty($parameters)) {
            $parameters = array(
                new AppendToResponse(array(
                    AppendToResponse::CREDITS,
                    AppendToResponse::EXTERNAL_IDS,
                    AppendToResponse::IMAGES,
                ))
            );
        }

        $data = $this->getApi()->getEpisode($tvShow, $season, $episode, $this->parseQueryParameters($parameters), $headers);

        return $this->getFactory()->create($data);
    }

    /**
     * Return the Seasons API Class
     *
     * @return \Tmdb\Api\TvEpisode
     */
    public function getApi()
    {
        return $this->getClient()->getTvEpisodeApi();
    }

    /**
     * @return TvEpisodeFactory
     */
    public function getFactory()
    {
        return new TvEpisodeFactory();
    }
}
