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
import { Category } from './category.entity';
import { Product } from '../products/product.entity';
import { CategoriesService } from './categories.service';
import { ProductsService } from '../products/products.service';
import { ApiParam, ApiTags } from '@nestjs/swagger';

@ApiTags('categories')
@Controller('categories')
export class CategoriesController {
  constructor(
    private readonly categoriesService: CategoriesService,
    private readonly productsService: ProductsService,
  ) {}

  @Get('')
  index(
    @Query('page') page = 1,
    @Query('limit') limit = 10,
  ): Promise<Pagination<Category>> {
    return this.categoriesService.paginate({ limit: limit, page: page });
  }

  @Get(':id')
  @ApiParam({ name: 'id', type: Number, description: 'ID category' })
  show(@Param('id') id: number): Promise<Category | null> {
    return this.categoriesService.findOne(id);
  }

  @Put(':id')
  @ApiParam({ name: 'id', type: String, description: 'ID category' })
  async update(
    @Param('id') id: string,
    @Body() categoryData: Category,
  ): Promise<Category> {
    const category = await this.categoriesService.findOne(+id);
    if (!category) {
      throw new NotFoundException(`Category with ID ${id} not found.`);
    }
    return this.categoriesService.update(+id, categoryData);
  }

  @Get(':id/products')
  @ApiParam({ name: 'id', type: String, description: 'ID категорії' })
  async getProductsByCategory(@Param('id') id: string): Promise<Product[]> {
    const category = await this.categoriesService.findOne(+id);
    if (!category) {
      throw new NotFoundException(`Category with ID ${id} not found.`);
    }
    return this.productsService.findByCategory(id);
  }

  @Post('')
  store(@Body() categoryData: Category): Promise<Category> {
    return this.categoriesService.create(categoryData);
  }

  @Delete(':id')
  @ApiParam({ name: 'id', type: Number, description: 'ID product' })
  async delete(@Param('id') id: number): Promise<void> {
    const result = await this.categoriesService.remove(id);
    if (result.affected === 0) {
      throw new NotFoundException(`Category #${id} not found.`);
    }
  }
}
