<?php
namespace Drest\Service\Action;

use Doctrine\ORM;
use DrestCommon\ResultSet;
use DrestCommon\Response\Response;

/**
 *
 * Delete an element action
 * @author Lee
 *
 * Rather than selecting then deleting: We could possibly just delete on supplied parameters
 */
class DeleteElement extends AbstractAction
{

    public function execute()
    {
        $matchedRoute = $this->getMatchedRoute();
        $classMetaData = $matchedRoute->getClassMetaData();
        $elementName = $classMetaData->getEntityAlias();

        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder()->select($elementName)->from($classMetaData->getClassName(), $elementName);
        foreach ($matchedRoute->getRouteParams() as $key => $value) {
            $qb->andWhere($elementName . '.' . $key . ' = :' . $key);
            $qb->setParameter($key, $value);
        }

        try {
            $object = $qb->getQuery()->getSingleResult(ORM\Query::HYDRATE_OBJECT);
        } catch (\Exception $e) {
            return $this->handleError($e, Response::STATUS_CODE_404);
        }

        try {
            $em->remove($object);
            $em->flush();

            $this->getResponse()->setStatusCode(Response::STATUS_CODE_200);

            return ResultSet::create(array('successfully deleted'), 'response');
        } catch (\Exception $e) {
            return $this->handleError($e, Response::STATUS_CODE_500);
        }
    }
}