<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Exceptions\MethodNotFoundException;
use App\Exceptions\RouteNotFoundException;
use App\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
  private Router $router;

  protected function setUp(): void
  {
    parent::setUp();
    $this->router = new Router();
  }
  /** @test */
  public function it_registers_a_route(): void
  {
    //given we have router object
    //when we call register method
    $this->router->register('get', '/users', ['Users', 'index']);

    $expected = [
      'get' => [
        '/users' =>  ['Users', 'index'],
      ]
    ];
    //then we assert route was registered
    $this->assertSame($expected, $this->router->routes());
  }

  /** @test */
  public function it_registers_get_route(): void
  {
    //given we have router object
    //when we call register method
    $this->router->get('/users', ['Users', 'index']);


    $expected = [
      'get' => [
        '/users' =>  ['Users', 'index'],
      ]
    ];
    //then we assert route was registered
    $this->assertSame($expected, $this->router->routes());
  }

  /** @test */
  public function it_registers_post_route(): void
  {
    //given we have router object
    //when we call register method
    $this->router->post('/users', ['Users', 'store']);

    $expected = [
      'post' => [
        '/users' =>  ['Users', 'store'],
      ]
    ];
    //then we assert route was registered
    $this->assertSame($expected, $this->router->routes());
  }

  /** @test */
  public function there_are_no_routes_when_router_is_created(): void
  {
    $router = new Router();
    $this->assertEmpty($router->routes());
  }

  /** @test 
   * @dataProvider routerNotFoundCases
   * */

  public function it_throws_route_not_found_exception(string $requestUri, string $requestMethod): void
  {
    $users = new class()
    {
      public function delete(): bool
      {
        return true;
      }
    };

    $this->router->get('/users', ['User', 'index']);
    //$this->router->post('/users', [$users::class, 'store']);
    $this->expectException(RouteNotFoundException::class);
    $this->router->resolve($requestUri, $requestMethod);
  }

  public static function routerNotFoundCases(): array
  {
    return [
      ['/users', 'put'], // handles $action not found
      ['/invoices', 'post'], //handles $url not found
      ['/users', 'get'], //handles class not found
    ];
  }

  /** @test 
   * */

  public function it_throws_method_not_found_exception(): void
  {

    $users = new class()
    {
      public function delete(): bool
      {
        return true;
      }
    };

    $requestUri = "/users";
    $requestMethod = "post";

    $this->router->post('/users', [$users::class, 'store']);
    $this->expectException(MethodNotFoundException::class);
    $this->router->resolve($requestUri, $requestMethod);
  }

  /** @test **/
  public function it_resolves_routes_from_closures(): void
  {
    $this->router->get('/users', fn () => [1, 2, 3]);
    $this->assertSame([1, 2, 3], $this->router->resolve('/users', 'get'));
  }

  /** @test **/
  public function it_resolves_routes(): void
  {
    $users = new class()
    {
      public function index(): array
      {
        return [1, 2, 3];
      }
    };
    $this->router->get('/users', [$users::class, 'index']);
    $this->assertSame([1, 2, 3], $this->router->resolve('/users', 'get'));
  }
}
