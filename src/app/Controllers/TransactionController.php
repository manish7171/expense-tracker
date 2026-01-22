<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\EntityManagerServiceInterface;
use App\Contracts\RequestValidatorFactoryInterface;
use App\DataObjects\TransactionData;
use App\Entity\Receipt;
use App\Entity\Transaction;
use App\Enums\TransactionType;
use App\RequestValidators\TransactionRequestValidator;
use App\RequestValidators\RecurringExpensesRequestValidator;
use App\ResponseFormatter;
use App\Services\CategoryService;
use App\Services\RequestService;
use App\Services\TransactionService;
use DateTime;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

class TransactionController
{
    public function __construct(
        private readonly Twig $twig,
        private readonly RequestValidatorFactoryInterface $requestValidatorFactory,
        private readonly TransactionService $transactionService,
        private readonly ResponseFormatter $responseFormatter,
        private readonly RequestService $requestService,
        private readonly CategoryService $categoryService,
        private readonly EntityManagerServiceInterface $entityManagerService
    ) {}

    public function index(Response $response): Response
    {
        return $this->twig->render(
            $response,
            'transactions/index.twig',
            ['categories' => $this->categoryService->getCategoryNames()]
        );
    }

    public function store(Request $request, Response $response): Response
    {
        $data = $this->requestValidatorFactory->make(TransactionRequestValidator::class)->validate(
            $request->getParsedBody()
        );
        $amount = $data['transaction-type'] === TransactionType::EXPENSE ? (float) (-1 * abs((float)$data['amount'])) : (float) $data['amount'];
        $transaction = $this->transactionService->create(
            new TransactionData(
                $data['description'],
                $amount,
                $data['transaction-type'],
                new DateTime($data['date']),
                $data['category']
            ),
            $request->getAttribute('user')
        );

        $this->entityManagerService->sync($transaction);

        return $response;
    }

    public function delete(Response $response, Transaction $transaction): Response
    {
        $this->entityManagerService->delete($transaction, true);

        return $response;
    }

    public function get(Response $response, Transaction $transaction): Response
    {
        $data = [
            'id'          => $transaction->getId(),
            'description' => $transaction->getDescription(),
            'amount'      => $transaction->getAmount(),
            'date'        => $transaction->getDate()->format('Y-m-d\TH:i'),
            'category'    => $transaction->getCategory()?->getId(),
            'transaction-type'        => $transaction->getTransactionType()
        ];

        return $this->responseFormatter->asJson($response, $data);
    }

    public function update(Request $request, Response $response, Transaction $transaction): Response
    {
        $data = $this->requestValidatorFactory->make(TransactionRequestValidator::class)->validate(
            $request->getParsedBody()
        );
        $amount = $data['transaction-type'] === TransactionType::EXPENSE ? (float) (-1 * abs((float)$data['amount'])) : (float) $data['amount'];
        $this->entityManagerService->sync(
            $this->transactionService->update(
                $transaction,
                new TransactionData(
                    $data['description'],
                    $amount,
                    $data['transaction-type'],
                    new DateTime($data['date']),
                    $data['category']
                )
            )
        );

        return $response;
    }

    public function load(Request $request, Response $response): Response
    {
        $params       = $this->requestService->getDataTableQueryParameters($request);
        //var_dump($params);
        //die;
        $transactions = $this->transactionService->getPaginatedTransactions($params);
        $totalExpense = $this->transactionService->getTotalExpense($params);
        $totalIncome = $this->transactionService->getTotalIncome($params);

        $transformer  = function (Transaction $transaction) {
            return [
                'id'          => $transaction->getId(),
                'description' => $transaction->getDescription(),
                'amount'      => $transaction->getAmount(),
                'date'        => $transaction->getDate()->format('m/d/Y g:i A'),
                'category'    => $transaction->getCategory()?->getName(),
                'type'        => $transaction->getTransactionType() ?? '',
                'wasReviewed' => $transaction->wasReviewed(),
                'receipts'    => $transaction->getReceipts()->map(fn(Receipt $receipt) => [
                    'name' => $receipt->getFilename(),
                    'id'   => $receipt->getId(),
                ])->toArray(),
            ];
        };

        $totalTransactions = count($transactions);

        return $this->responseFormatter->asDataTable(
            $response,
            array_map($transformer, (array) $transactions->getIterator()),
            $params->draw,
            $totalTransactions,
            $totalExpense,
            $totalIncome
        );
    }

    public function toggleReviewed(Response $response, Transaction $transaction): Response
    {
        $this->transactionService->toggleReviewed($transaction);
        $this->entityManagerService->sync();

        return $response;
    }


    public function addDefaultExpenses(Request $request, Response $response): Response
    {
        //electricity:81; deutschland ticket angee: 59,85;Manish sim:14.99;Angee sim:6.99;rent:577.45;internet:24.89;ard radio tax:18.36
        $data = [
            ["description" => "Electricity", "category_id" => 9, "category" => "electricity", "price" => 81],
            ["description" => "Deutschland ticket angee", "category_id" => 5, "category" => "transport", "price" => 59.85],
            ["description" => "Manish sim", "category_id" => 14, "category" => "Sim Card", "price" => 14.99],
            ["description" => "Angee sim", "category_id" => 14, "category" => "Sim Card", "price" => 6.99],
            ["description" => "Rent", "category_id" => 8, "category" => "Rent", "price" => 577.45],
            ["description" => "Internet", "category_id" => 10, "category" => "Internet", "price" => 24.89],
            ["description" => "Ard radio tax", "category_id" => 15, "category" => "radio tax", "price" => 18.36],
        ];

        return $this->twig->render(
            $response,
            'transactions/default_expenses.twig',
            [
                'data' => $data,
                'categories' => $this->categoryService->getCategoryNames()
            ]
        );
    }

    public function insertDefaultExpenses(Request $request, Response $response): Response
    {
        echo "STORE";
        return $response;
    }

    public function addRecurringExpenses(Request $request, Response $response): Response
    {
        //electricity:81; deutschland ticket angee: 59,85;Manish sim:14.99;Angee sim:6.99;rent:577.45;internet:24.89;ard radio tax:18.36
        $data = [
            ["description" => "Electricity", "category_id" => 9, "category" => "electricity", "price" => 81],
            ["description" => "Deutschland ticket angee", "category_id" => 5, "category" => "transport", "price" => 59.85],
            ["description" => "Manish sim", "category_id" => 14, "category" => "Sim Card", "price" => 14.99],
            ["description" => "Angee sim", "category_id" => 14, "category" => "Sim Card", "price" => 6.99],
            ["description" => "Rent", "category_id" => 8, "category" => "Rent", "price" => 577.45],
            ["description" => "Internet", "category_id" => 10, "category" => "Internet", "price" => 24.89],
            ["description" => "Ard radio tax", "category_id" => 15, "category" => "radio tax", "price" => 18.36],
        ];

        return $this->twig->render(
            $response,
            'transactions/add_recurring_expenses.twig',
            [
                'data' => $data,
                'categories' => $this->categoryService->getCategoryNames()
            ]
        );
    }

    public function saveRecurringExpenses(Request $request, Response $response): Response
    {
        $data = $this->requestValidatorFactory->make(RecurringExpensesRequestValidator::class)->validate(
            $request->getParsedBody()
        );
        $amount = $data['transaction-type'] === TransactionType::EXPENSE ? (float) (-1 * abs((float)$data['amount'])) : (float) $data['amount'];
        $transaction = $this->transactionService->createRecurringExpense(
            new TransactionData(
                $data['description'],
                $amount,
                $data['transaction-type'],
                new DateTime(),
                $data['category']
            ),
            $request->getAttribute('user')
        );

        $this->entityManagerService->sync($transaction);
        return $response
            ->withHeader('Location', '/transactions/add-recurring-expenses')
            ->withStatus(302);
    }

    public function runRecurringTransactions(Request $request, Response $response): Response
    {
        // check last recuring date for each
        // if monthly, check current month with last updated month
        // if last updated month is not defined then its the first run so run for all year and month till this month
        // TODO: we need to do this with incomes as well
        echo "Run";
        $recurrings = $this->transactionService->getAllRecurringTransaction();
        $years = [2024, 2025];
        $user = $request->getAttribute('user');
        $batchSize = 20;
        $count = 0;

        foreach ($recurrings as $recurring) {
            echo "===Recurring transaction ".$recurring['description'] ."<br><br>";
            if (!empty($recurring['lastGenerated'])) {
                continue;
            }

            $category = $this->categoryService->getById($recurring['categoryId']);

            foreach ($years as $year) {
                for ($month = 1; $month <= 12; $month++) {
                    $date = new DateTime(sprintf('%d-%02d-01', $year, $month));
                    
                    echo "      Recurring transaction ".$date->format('Y-m-d') ."<br>";
                    if ($year === 2025 && in_array($month, [1,2,3,4]) && $recurring['categoryId'] == 16) {
                        continue;
                    }
                    $transaction = $this->transactionService->create(
                        new TransactionData(
                            $recurring['description'],
                            (float) $recurring['amount'],
                            $recurring['type'],
                            $date,
                            $category
                        ),
                        $user
                    );

                    $this->entityManagerService->sync($transaction);
                }
            }

            echo "      update Recurring laste generated <br>";
            $this->transactionService->updateLastGenerateColumn($recurring['id']);
            echo "===Recurring ends <br><br>";
        }

        // Flush remaining entities
        $this->entityManagerService->flush();
        return $response;
    }
}
