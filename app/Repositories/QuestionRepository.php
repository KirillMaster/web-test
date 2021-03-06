<?php

namespace Repositories;

use Doctrine\ORM\EntityManager;
use PaginationResult;
use Question;
use TestTheme;
use Theme;
use Doctrine\ORM\Query\Expr\Join;

class QuestionRepository extends BaseRepository
{
    public function __construct(EntityManager $em)
    {
        parent::__construct($em, Question::class);
    }

    public function getByParamsPaginated($pageSize, $pageNum, $themeId,
                                               $text, $type, $complexity){
        $qb = $this->repo->createQueryBuilder('q');
        $query = $qb;

        $query = $query->andWhere('q.theme = :themeId')
            ->setParameter('themeId', $themeId);

        if ($text != null && $text != ''){
            $query = $query->andWhere('q.text LIKE :text')
                ->setParameter('text', '%'.$text.'%');
        }

        if ($type != null && $type != ''){
            $query = $query->andWhere('q.type = :type')
                ->setParameter('type', $type);
        }

        if ($complexity != null && $complexity != ''){
            $query = $query->andWhere('q.complexity = :complexity')
                ->setParameter('complexity', $complexity);
        }
        
        $countQuery = clone $query;
        $data =  $this->paginate($pageSize, $pageNum, $query, 'q.id');

        $count = $countQuery->select(
            $qb->expr()
                ->count('q.id'))
            ->getQuery()
            ->getSingleScalarResult();

        return new PaginationResult($data, $count);
    }

    public function getNotAnsweredQuestionsByTest($testId, $answeredIds, $timeLeft){
        $qb = $this->repo->createQueryBuilder('q');
        $query = $qb->join(Theme::class, 't', Join::WITH, 'q.theme = t.id')
            ->join(TestTheme::class, 'tt', Join::WITH,
                't.id = tt.theme AND tt.test = :test AND q.time < :timeLeft')
            ->setParameter('test', $testId)
            ->setParameter('timeLeft', $timeLeft);

        if ($answeredIds != null && !empty($answeredIds)){
            $query = $query->andWhere('q.id NOT IN(:answered)')
                ->setParameter('answered', $answeredIds);
        }

        $query = $query->select('q.id')
            ->getQuery();

        return $query->getScalarResult();
    }

    public function getByTest($testId){
        $qb = $this->repo->createQueryBuilder('q');
        $query = $qb->join(Theme::class, 't', Join::WITH, 'q.theme = t.id')
            ->join(TestTheme::class, 'tt', Join::WITH,
                't.id = tt.theme AND tt.test = :test')
            ->setParameter('test', $testId)
            ->getQuery();

        return $query->execute();
    }

    public function getByTheme($themeId){
        return $this->repo->findBy(['theme' => $themeId]);
    }
}