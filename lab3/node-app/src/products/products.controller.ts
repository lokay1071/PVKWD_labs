import {
  Body,
  Controller,
  Delete,
  Get,
  NotFoundException,
  Param,
  Post,
  Put,
  Query,
} from '@nestjs/common';
import { Pagination } from 'nestjs-typeorm-paginate';
import { Product } from './product.entity';
import { ProductsService } from './products.service';
import { ApiParam, ApiTags } from '@nestjs/swagger';
import { Roles } from 'nest-keycloak-connect';

@ApiTags('products')
@Controller('products')
export class ProductsController {
  constructor(private readonly productsService: ProductsService) {}

  @Get('')
  @Roles({ roles: ['realm:products-role'] })
  index(
    @Query('page') page = 1,
    @Query('limit') limit = 10,
  ): Promise<Pagination<Product>> {
    return this.productsService.paginate({ limit: limit, page: page });
  }

  @Get(':id')
  @Roles({ roles: ['realm:products-role'] })
  @ApiParam({ name: 'id', type: Number, description: 'ID product' })
  show(@Param('id') id: number): Promise<Product | null> {
    return this.productsService.findOne(id);
  }

  @Put(':id')
  @Roles({ roles: ['realm:products-role'] })
  @ApiParam({ name: 'id', type: String, description: 'ID product' })
  async update(
    @Param('id') id: string,
    @Body() productData: Product,
  ): Promise<Product> {
    const product = await this.productsService.findOne(+id);
    if (!product) {
      throw new NotFoundException(`Product with ID ${id} not found.`);
    }
    return this.productsService.update(+id, productData);
  }

  @Post('')
  @Roles({ roles: ['realm:products-role'] })
  store(@Body() productData: Product): Promise<Product> {
    return this.productsService.create(productData);
  }

  @Delete(':id')
  @Roles({ roles: ['realm:products-role'] })
  @ApiParam({ name: 'id', type: Number, description: 'ID product' })
  async delete(@Param('id') id: number): Promise<void> {
    const result = await this.productsService.remove(id);
    if (result.affected === 0) {
      throw new NotFoundException(`Product #${id} not found.`);
    }
  }
}
